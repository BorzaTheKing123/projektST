<?php

// app/Jobs/RebuildRiskCountersJob.php
namespace App\Domains\HeatmapJobs;

use App\Models\HeatmapModels\Risk;
use App\Models\HeatmapModels\RiskMention;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class RebuildRiskCountersJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        $counts = RiskMention::query()
            ->selectRaw('risk_id, COUNT(*) as mc, COUNT(DISTINCT article_id) as ac')
            ->groupBy('risk_id')
            ->get();

        foreach ($counts as $row) {
            Risk::query()->where('id', $row->risk_id)->update([
                'mention_count' => $row->mc,
                'article_count' => $row->ac,
            ]);
        }
    }
}
