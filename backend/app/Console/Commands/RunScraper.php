<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;

class RunScraper extends Command
{
    protected $signature = 'scraper:loop';
    protected $description = 'Neprekinjeno zaganja Python scraper vsakih 10 minut';

    public function handle(): void
    {
        set_time_limit(0);

        $this->info('🔁 Scraper loop se je začel. Vsakih 10 minut bo sprožen.');

        while (true) {
            $this->runScraperOnce();

            $this->info('⏳ Čakam 10 minut do naslednjega zagona...');
            sleep(600); // 600 sekund = 10 minut
        }
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