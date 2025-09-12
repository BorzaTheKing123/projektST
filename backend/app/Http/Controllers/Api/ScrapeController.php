<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class ScrapeController extends Controller
{
    /**
     * Executes the Python scraper and returns the JSON output.
     *
     * @return JsonResponse
     */
    public function runScraper(): JsonResponse
    {
        // 1. Določite absolutno pot do Python izvajalca.
        // To je bolj zanesljivo kot uporaba samo 'python3'.
        // Uporabite env() za pot do virtualnega okolja.
        $pythonPath = base_path('venv/bin/python');
        
        // 2. Določite pravilno pot do skripte.
        // Predvidevamo, da je mapa 'scraper' v korenu projekta.
        $scriptPath = base_path('scraper/scraper24ur.py');

        // Preverite, ali poti obstajajo.
        if (!file_exists($pythonPath)) {
            Log::error("Python izvajalec ne obstaja na: " . $pythonPath);
            return response()->json([
                'error' => 'Napaka strežnika',
                'details' => 'Python izvajalec ni najden.'
            ], 500);
        }

        if (!file_exists($scriptPath)) {
            Log::error("Scraper skripta ne obstaja na: " . $scriptPath);
            return response()->json([
                'error' => 'Napaka strežnika',
                'details' => 'Scraper skripta ni najdena.'
            ], 500);
        }

        // 3. Uporabite Symfony Process za zanesljivo izvajanje.
        $process = new Process([$pythonPath, $scriptPath]);
        
        // Izvedite proces in zajemite izpis.
        try {
            $process->mustRun();
        } catch (ProcessFailedException $exception) {
            // Zajemite napake iz Python skripte.
            Log::error('Napaka pri izvajanju scraperja: ' . $exception->getMessage());
            return response()->json([
                'error' => 'Scraper ni uspel',
                'details' => $process->getErrorOutput()
            ], 500);
        }

        // 4. Zajemite izpis.
        $output = trim($process->getOutput()); // trim odstrani prazne vrstice
        $data = json_decode($output, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            Log::error('Neveljaven JSON izpis iz Python skripte: ' . $output);
            return response()->json([
            'error' => 'Neveljaven JSON izpis',
            'details' => 'Izpis Python skripte ni bil veljaven JSON.'
            ], 500);
        }

        Log::info('Scraper output: ' . substr($output, 0, 500));

        if (count($data) !== 0) {
            foreach ($data as $article) {
                new ScrapeToAiController()->article($article);
            }

            return response()->json([
                'status' => 'Scraper zagnan',
                'output' => $data
            ]);
        }

        return response()->json([
            'status' => 'Scraper zagnan',
            'output' => 'Ni novih novic'
        ]);
    }
}