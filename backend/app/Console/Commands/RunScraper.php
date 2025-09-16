<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;

class RunScraper extends Command
{
    protected $signature = 'scraper:run';
    protected $description = 'Neprekinjeno zaganja Python scraper vsakih 10 minut';

    public function handle()
    {
        $this->info('🚀 Zagon scraperja...');

        // Pokliči metodo, ki izvede scraper enkrat
        $this->runScraperOnce();

        $this->info('✅ Scraper se je uspešno zaključil.');

        return Command::SUCCESS;
    }

    private function runScraperOnce(): void
    {
        try {
            app()->call('App\Http\Controllers\Api\ScrapeController@runScraper');
            $this->info('✅ Scraper uspešno zagnan prek ScraperController');
        } catch (\Throwable $e) {
            Log::error('❌ Napaka pri zagonu scraperja iz kontrolerja', ['error' => $e->getMessage()]);
            $this->error('Scraper ni uspel.');
        }
    }

}