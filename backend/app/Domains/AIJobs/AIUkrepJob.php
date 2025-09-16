<?php

namespace App\Domains\AIJobs;

use Illuminate\Support\Facades\Log;
use App\Models\Tveganje;

class AIUkrepJob
{
    /**
     * Konstruktor za injektiranje odvisnosti ali prejem podatkov.
     */
    public function __construct(private $request, private $response)
    {
        //
    }

    /**
     * Izvede glavno logiko.
     *
     * @param  mixed  ...$parameters
     * @return mixed
     */
    public function handle()
    {
        Log::info('OpenAI response:', ['response' => $this->response->json()]);

        $tveganjeId = $this->request->input('tveganje_id');
        $tveganjeModel = Tveganje::find($tveganjeId);

        if (!$tveganjeModel) {
            Log::error('Tveganje z ID ni bilo najdeno.', ['id' => $tveganjeId]);
            return response()->json(['error' => 'Tveganje ni bilo najdeno.'], 404);
        }

        $obstojeciUkrepi = $tveganjeModel->ukrepi ?? '';
        $noviUkrepi = $this->response;

        if (!$noviUkrepi || trim($noviUkrepi) === '') {
            Log::warning('AI vrnil prazen odgovor.', ['response' => $this->response->json()]);
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
