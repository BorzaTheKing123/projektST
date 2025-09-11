<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\HeatmapModels\RiskMention;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;


class ScrapeToAiController extends Controller
{
    public function article(Request $request)
    {

        $link = $request->input('link');
        $summary = $request->input('summary');
        $text = $request->input('text');
        $articleId = $request->input('article_id'); // pričakujemo iz frontenda
        $riskId = $request->input('risk_id');       // pričakujemo iz frontenda


        $prompt = <<<EOT
        Si strokovnjak za varnost in tveganja. Na podlagi spodnjih podatkov(povezavo, povzetek in besedilo) razvrsti vsako izmed tveganj v sledeče kategorije:

        Podatki:
        Link: {$link}
        Povzetek: {$summary}
        Besedilo: {$text}

        Kategorije tveganj so:

        ##Geopolitična in Makroekonomska Tveganja
        Politična tveganja: Spremembe vlad, politična nestabilnost, protekcionizem, politični konflikti.

        Vojna in varnost: Oboroženi spopadi, terorizem, napadi na infrastrukturo.

        Makroekonomska tveganja: Recesija, inflacija, deflacija, spremembe obrestnih mer.

        Valutna tveganja: Nihanje tečajev in devalvacija valute.

        Regulativna tveganja: Nove zakonodaje, višji davki, strožji predpisi.

        Tržna tveganja: Nihanje cen na trgu (npr. surovine, delnice).

        ##Operativna in Tehnološka Tveganja
        Operativna tveganja: Napake v procesih, človeške napake, slab nadzor kakovosti.

        Tveganja dobavne verige: Zamude, pomanjkanje materialov, zanesljivost dobaviteljev.

        Tehnološka tveganja: Okvare strojev, izpadi programske opreme, zastarela tehnologija.

        Kibernetska tveganja: Vdori, izsiljevalski virusi, phishing, kraje podatkov.

        Tveganja IT-infrastrukture: Izpadi strežnikov, napake v omrežju.

        Tveganja informacijske varnosti: Uhajanje zaupnih podatkov, nepooblaščen dostop.

        ##Okoljska in Družbena Tveganja (ESG)
        Okoljska tveganja: Naravne katastrofe (potresi, poplave), podnebne spremembe, onesnaženje, pomanjkanje virov.

        Zdravstvena tveganja: Pandemije, izbruhi bolezni, zdravstvene krize.

        Družbena tveganja: Delavski spori, družbeni nemiri, kršitve človekovih pravic.

        Tveganja ugleda: Škandal, slab PR, izguba zaupanja strank in javnosti.

        Etična tveganja: Neetično poslovanje, korupcija, prevare.

        ##Finančna in Pravna Tveganja
        Kreditno tveganje: Neplačila dolga s strani strank ali partnerjev.

        Likvidnostno tveganje: Nezmožnost izpolnjevanja kratkoročnih finančnih obveznosti.

        Tveganja skladnosti s predpisi: Neupoštevanje zakonov in regulativnih zahtev, kar vodi v kazni ali globe.

        Pravna tveganja: Tožbe, spori, neizpolnjene pogodbe.

        ##Tveganja na ravni projekta
        Tveganja projekta: Preseganje proračuna, zamude, spremembe obsega projekta.

        Tveganja pomanjkanja znanja: Nepravilno razporejeni viri, pomanjkanje usposobljenega kadra.

        Odgovori v obliki JSON z naslednjo strukturo:
        {
            'link': "povezava ki sva ti jo dovedla",
            'category': "ena izmed zgoraj navedenih kategorij",    
            'summary': "tvoja obnova clanka dolga do 20 besed",
            'confidence': "koliko si prepričan da je to prava kategorija",
        }

        Začni zdaj:
        EOT;


         Log::info('AI prompt:', ['prompt' => $prompt]);

        $openaiKey = config('app.openai_api_key');

        $aiResponse = Http::withHeaders([
            'Authorization' => 'Bearer ' . $openaiKey,
            'Content-Type' => 'application/json',
        ])->post('https://api.openai.com/v1/chat/completions', [
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

        $mention = RiskMention::create([
            'article_id' => $articleId,
            'risk_id'    => $riskId,
            'confidence' => $parsed['confidence'] ?? null,
            'spans'      => json_encode($parsed),
        ]);

        return response()->json([
            'status' => 'success',
            'risk_mention' => $mention
        ]);
    }
}
