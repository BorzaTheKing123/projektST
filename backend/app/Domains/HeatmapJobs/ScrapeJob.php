<?php

namespace App\Domains\HeatmapJobs;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

use App\Features\AIFeatures\AIScrapeAnalyzeFeature;

class ScrapeJob
{ 
    public function handle(): JsonResponse
    {
        $pythonPath = base_path(env('PYTHON_PATH'));
        $paths = [base_path('scraper/scraperBBC.py'), base_path('scraper/scraper24ur.py')];
        // Hrani število predelanih člankov
        $output_count = 0;

        // Preverite, ali poti obstajajo.
        if (!file_exists($pythonPath)) {
            Log::error("Python izvajalec ne obstaja na: " . $pythonPath);
            return response()->json([
                'error' => 'Napaka strežnika',
                'details' => 'Python izvajalec ni najden.'
            ], 500);
        }

        foreach ($paths as $scriptPath) {
            if (!file_exists($scriptPath)) {
                Log::error("Scraper skripta ne obstaja na: " . $scriptPath);
                return response()->json([
                    'error' => 'Napaka strežnika',
                    'details' => 'Scraper skripta ni najdena.'
                ], 500);
            }

            $data = new RunPythonJob($pythonPath, $scriptPath)->handle();

            if (count($data) !== 0) {
                foreach ($data as $article) {
                    $output_count += 1;
                    new AIScrapeAnalyzeFeature($article)->handle();
                }
            }
        }

        return response()->json([
            'description' => 'Število predelanih novic',
            'output' => $output_count
        ]);
    }
}