<?php

namespace App\Domains\HeatmapJobs;

use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class RunPythonJob
{
    public function __construct(private $pythonPath, private $scriptPath)
    {
        
    } 

    public function handle()
    {
        // Uporabite Symfony Process za zanesljivo izvajanje.
        $process = new Process([$this->pythonPath, $this->scriptPath]);
        
        // Izvedite proces in zajemite izpis.
        try {
            $process->mustRun();
        } catch (ProcessFailedException $exception) {
            // Zajemite napake iz Python skripte.
            Log::error('Napaka pri izvajanju python kode: ' . $exception->getMessage());
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
        return $data;
    }
}