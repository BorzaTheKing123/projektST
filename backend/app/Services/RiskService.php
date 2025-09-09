<?php
// app/Services/RiskService.php
namespace App\Services;

use App\Models\HeatmapModels\Risk;
use App\Models\HeatmapModels\RiskMention;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class RiskService
{
    public function getTopRisks(int $limit = 10): array
    {
        return Risk::query()
            ->orderByDesc('mention_count')
            ->limit($limit)
            ->get(['id as key', 'name', 'mention_count as count'])
            ->toArray();
    }

    public function upsertRisksAndMentions(int $articleId, array $llmRisks): void
    {
        // $llmRisks: [['name'=>'Kibernetski napad','slug'=>'cyberattack','category'=>'kibernetika','confidence'=>0.92], ...]
        DB::transaction(function () use ($articleId, $llmRisks) {
            $attachedRiskIds = [];

            foreach ($llmRisks as $r) {
                $slug = $r['slug'] ?? Str::slug(mb_strtolower($r['name']));
                $risk = Risk::query()->firstOrCreate(
                    ['slug' => $slug],
                    ['name' => $r['name'], 'category' => $r['category'] ?? null]
                );

                RiskMention::query()->updateOrCreate(
                    ['article_id' => $articleId, 'risk_id' => $risk->id],
                    ['confidence' => $r['confidence'] ?? 0, 'spans' => $r['spans'] ?? null]
                );

                $attachedRiskIds[] = $risk->id;
            }

            // Rebuild counters for risks touched in this article
            if ($attachedRiskIds) {
                $uniqueRiskIds = array_values(array_unique($attachedRiskIds));
                $mentionCounts = RiskMention::query()
                    ->selectRaw('risk_id, COUNT(*) as c, COUNT(DISTINCT article_id) as ac')
                    ->whereIn('risk_id', $uniqueRiskIds)
                    ->groupBy('risk_id')
                    ->pluck('c', 'risk_id')
                    ->toArray();

                $articleCounts = RiskMention::query()
                    ->selectRaw('risk_id, COUNT(DISTINCT article_id) as ac')
                    ->whereIn('risk_id', $uniqueRiskIds)
                    ->groupBy('risk_id')
                    ->pluck('ac', 'risk_id')
                    ->toArray();

                foreach ($uniqueRiskIds as $rid) {
                    Risk::query()->where('id', $rid)->update([
                        'mention_count' => $mentionCounts[$rid] ?? 0,
                        'article_count' => $articleCounts[$rid] ?? 0,
                    ]);
                }
            }
        });
    }
}