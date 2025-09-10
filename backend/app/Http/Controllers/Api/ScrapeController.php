<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;


class ScrapeController extends Controller
{
    public function runScraper()
{
    $pythonPath = env('PYTHON_PATH'); // npr. C:\\Users\\makst\\Herd\\projektST\\venv\\Scripts\\python.exe
    $scriptPath = base_path('../scraper/scraper24ur.py');

    $process = new Process([$pythonPath, $scriptPath]);
    $process->run();

    if (!$process->isSuccessful()) {
        Log::error('Scraper error: ' . $process->getErrorOutput());
        return response()->json([
            'error' => 'Scraper ni uspel',
            'details' => $process->getErrorOutput()
        ], 500);
    }

    $output = $process->getOutput();
    Log::info('Scraper output: ' . substr($output, 0, 500)); // če je predolg


    return response()->json([
        'status' => 'Scraper zagnan',
        'output' => json_decode($output, true)
    ]);
}
}