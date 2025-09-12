<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\Risk; // Predpostavljamo, da imate model 'Risk' za tabelo 'risks'
use App\Models\RiskMention; // Predpostavljamo, da imate model 'RiskMention' za tabelo 'risk_mentions'

class UpdateRiskArticleCount extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'risks:update-counts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Posodobi stolpec article_count v tabeli risks na podlagi števila pojavitev v risk_mentions.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Prikaz informacije, da se je program začel
        $this->info('Posodabljanje števila člankov za tveganja...');

        // 1. Preštejemo število pojavitev risk_id v tabeli risk_mentions
        $riskCounts = DB::table('risk_mentions')
            ->select('risk_id', DB::raw('count(*) as total'))
            ->groupBy('risk_id')
            ->pluck('total', 'risk_id');

        // Prikaz števila najdenih tveganj za posodobitev
        $this->info('Najdenih ' . $riskCounts->count() . ' unikatnih tveganj za posodobitev.');
        
        // 2. Iteriramo skozi preštete vrednosti in posodobimo tabelo risks
        foreach ($riskCounts as $riskId => $count) {
            // Uporabimo DB fasado za posodabljanje tabele
            DB::table('risks')
                ->where('id', $riskId)
                ->update(['article_count' => $count]);

            // Lahko tudi prikažemo napredek
            $this->line("Posodobljeno tveganje ID {$riskId} z vrednostjo {$count}.");
        }

        // 3. Obvestimo uporabnika, da je operacija končana
        $this->info('Posodabljanje je uspešno zaključeno!');

        return Command::SUCCESS;
    }
}