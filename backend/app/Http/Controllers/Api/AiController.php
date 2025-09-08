<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\Tveganje;

class AiController extends Controller
{
    public function predlogi(Request $request)
    {

        $tveganjeIme = $request->input('tveganje');
        $navodila = $request->input('navodila');
        $tveganjeId = $request->input('tveganje_id');

        $tveganjeModel = Tveganje::find($tveganjeId);

        if (!$tveganjeModel) {
            Log::error('Tveganje z ID ni bilo najdeno.', ['id' => $tveganjeId]);
            return response()->json(['error' => 'Tveganje ni bilo najdeno.'], 404);
        }

        $obstojeciUkrepi = $tveganjeModel->ukrepi ?? '';

$prompt = <<<EOT
Si strokovnjak za varnost in tveganja. Na podlagi spodnjih podatkov napiši **izključno** seznam kratkih, jedrnatih ukrepov.

Tveganje: "$tveganjeIme"
Navodila: "$navodila"

⚠️ Pomembno:
- Ne dodaj uvoda, razlage ali zaključka.
- Vsak ukrep naj bo v svoji vrstici.
- Vsak ukrep naj se konča z vejico.
- Ne uporabljaj številk ali alinej.

Primer:
Gasilni aparat,
Strelovod,
Označene poti,

Začni zdaj:
EOT;


        Log::info('AI prompt:', ['prompt' => $prompt]);

        $openaiKey = config('app.openai_api_key');

        Log::info('Pošiljam OpenAI zahtevek z Authorization header:', [
            'Authorization' => 'Bearer ' . $openaiKey
        ]);

        $response = Http::withHeaders([
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

        Log::info('OpenAI response:', ['response' => $response->json()]);

        $noviUkrepi = data_get($response->json(), 'choices.0.message.content');

        if (!$noviUkrepi || trim($noviUkrepi) === '') {
            Log::warning('AI vrnil prazen odgovor.', ['response' => $response->json()]);
            return response()->json(['predlogi' => 'Ni predlogov.']);
        }

        $zdruzeniUkrepi = trim($obstojeciUkrepi) . "\n" . trim($noviUkrepi);

        $tveganjeModel->ukrepi = $zdruzeniUkrepi;
        $tveganjeModel->save();

        return response()->json([
            'predlogi' => $noviUkrepi,
            'ukrepi' => $zdruzeniUkrepi
        ]);
    }
}