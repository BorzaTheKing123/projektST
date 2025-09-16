<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\HeatmapModels\RiskMention;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use App\Models\HeatmapModels\Risk;


class ScrapeToAiController extends Controller
{
    public function article($data)
    {
        set_time_limit(600);

        $link = $data["link"];
        $summary = $data['summary'];
        $text = $data['text'];


        $prompt = <<<EOT
        VLOGA:
        Deluj kot visoko usposobljen strokovnjak za analizo varnostnih tveganj.

        CILJ:
        Tvoja naloga je, da na podlagi posredovanih podatkov o novici (povezava, povzetek in besedilo) identificiraš **glavno oziroma prevladujoče tveganje**, opisano v vsebini, ga uvrstiš v eno izmed spodaj navedenih kategorij in pripraviš odgovor v strogo določenem formatu JSON.

        ---

        VHODNI PODATKI:
        * **Link:** {$link}
        * **Povzetek:** {$summary}
        * **Besedilo:** {$text}

        ---

        NAVODILA ZA ANALIZO:
        1.  **Preberi in razumi vsebino:** Natančno preuči posredovano besedilo, da razumeš osrednji dogodek ali temo.
        2.  **Identificiraj glavno tveganje:** Osredotoči se na **največ 3** najpomembnejša tveganja, ki izhajajo iz vsebine. Če je tveganj več, izberi tista 3, ki so najbolj poudarjena.
        3.  **Dodeli kategorijo:** Izberi najustreznejše kategorije iz spodnjega seznama. Uporabiš lahko **samo eno** kategorijo na 1 JSON zapis.

        ---

        SEZNAM DOVOLJENIH KATEGORIJ Tveganj:
        Uporabi **točno eno** izmed naslednjih vrednosti za ključ `category`. Ime kategorije mora biti zapisano z veliko začetnico, ostale črke pa z malo.

        * `Politična tveganja`
        * `Vojna in varnost`
        * `Makroekonomska tveganja`
        * `Valutna tveganja`
        * `Regulativna tveganja`
        * `Tržna tveganja`
        * `Operativna tveganja`
        * `Tveganja dobavne verige`
        * `Tehnološka tveganja`
        * `Kibernetska tveganja`
        * `Tveganja IT-infrastrukture`
        * `Tveganja informacijske varnosti`
        * `Okoljska tveganja`
        * `Zdravstvena tveganja`
        * `Družbena tveganja`
        * `Tveganja ugleda`
        * `Etična tveganja`
        * `Kreditno tveganje`
        * `Likvidnostno tveganje`
        * `Tveganja skladnosti s predpisi`
        * `Pravna tveganja`
        * `Tveganja projekta`
        * `Tveganja pomanjkanja znanja`

        ---

        ZAHTEVAN IZHODNI FORMAT (JSON):
        Odgovor **mora biti** v formatu JSON in vsebovati seznam (list) z enim slovarjem (dictionary). Ne dodajaj nobenega dodatnega besedila ali pojasnil izven JSON strukture.

        **Struktura slovarja:**
        * `link` (string): Uporabi natančno vrednost, ki je bila posredovana v vhodnih podatkih.
        * `category` (string): Ena izmed vrednosti iz zgoraj navedenega seznama dovoljenih kategorij.
        * `summary` (string): Tvoj lasten, kratek povzetek osrednjega tveganja, dolg **največ 20 besed**.
        * `confidence` (float): Decimalno število med **0.0 in 1.0**, ki predstavlja tvojo stopnjo zanesljivosti pri določanju kategorije (npr. 0.75, 0.9, 1.0).

        ---

        PRIMER ZAHTEVANEGA JSON ODGOVORA:
        [
            {
                'link': 'https://www.24ur.com/novice/tujina/silovito-neurje-v-mostarju-na-reki-so-se-ulice-spremenile-v-hudournike.html',
                'category': 'Okoljska tveganja',
                'summary': 'Obilne padavine in neurja so povzročila obsežne poplave in materialno škodo v več regijah.',
                'confidence': 1.0
            },
            {
                'link': 'https://www.24ur.com/novice/tujina/silovito-neurje-v-mostarju-na-reki-so-se-ulice-spremenile-v-hudournike.html',
                'category': 'Operativna tveganja',
                'summary': 'Poplave in zemeljski plazovi otežujejo vožnjo in prekinjajo trajektne, katamaranske in ladijske linije.',
                'confidence': 0.9
            },
            {
                'link': 'https://www.24ur.com/novice/tujina/silovito-neurje-v-mostarju-na-reki-so-se-ulice-spremenile-v-hudournike.html',
                'category': 'Tveganja projekta',
                'summary': 'Nujne službe so imele zaradi poplavljenih cest in prostorov čez dan okoli 60 intervencij.',
                'confidence': 0.8
            }
        ]
        EOT;


        // Log::info('AI prompt:', ['prompt' => $prompt]);

        $openaiKey = config('app.openai_api_key');

        $aiResponse = Http::withHeaders([
            'Authorization' => 'Bearer ' . $openaiKey,
            'Content-Type' => 'application/json',
        ])->timeout(600)
        ->post('https://api.openai.com/v1/chat/completions', [
            'model' => 'gpt-4',
            'messages' => [
                ['role' => 'system', 'content' => 'Si strokovnjak za varnost in tveganja.'],
                ['role' => 'user', 'content' => $prompt],
            ],
            'temperature' => 0.5,
        ]);

        $content = data_get($aiResponse->json(), 'choices.0.message.content');

        if (!$content || trim($content) === '') {
            Log::warning('AI vrnil prazen odgovor.');
            return response()->json(['error' => 'Ni predlogov.']);
        }

        // Normaliziramo JSON (če AI vrne enojne narekovaje)
        $normalized = str_replace("'", '"', $content);
        $parsed = json_decode($normalized, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            Log::error('AI odgovor ni veljaven JSON', ['content' => $content]);
            return response()->json(['error' => 'Neveljaven AI JSON odgovor.']);
        }

        Log::info($parsed);

        foreach ($parsed as $pars) {
            $risk = Risk::where('category', $pars['category'])->first();

            RiskMention::create([
            'risk_id'    => $risk->id,
            'confidence' => $pars['confidence'] ?? null,
            'link' => $link,
            'summary' => $pars['summary'] ?? null,
        ]);
        }

        return response()->json([
            'status' => 'success'
        ]);
    }
}
