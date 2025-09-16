<?php

// app/Jobs/ProcessArticleJob.php
namespace App\Domains\HeatmapJobs;

use App\Models\HeatmapModels\Article;
use App\Services\LLMService;
use App\Services\RiskService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessArticleJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public int $articleId) {}

    public function handle(LLMService $llm, RiskService $risks): void
    {
        $article = Article::query()->find($this->articleId);
        if (!$article) return;

        $llmRisks = $llm->analyzeArticle($article->content);
        if (!is_array($llmRisks) || empty($llmRisks)) return;

        $risks->upsertRisksAndMentions($article->id, $llmRisks);
    }
}