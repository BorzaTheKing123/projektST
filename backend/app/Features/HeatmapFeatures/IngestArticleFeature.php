<?php
// app/Features/IngestArticleFeature.php
namespace App\Features\HeatmapFeatures;

use App\Domains\HeatmapJobs\ProcessArticleJob;
use App\Models\HeatmapModels\Article;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

class IngestArticleFeature
{
    public function __construct(
        private ?string $title,
        private ?string $content,
        private ?string $source = null,
        private ?string $externalId = null,
        private ?string $publishedAt = null,
    ) {}

    public function handle(): JsonResponse
    {
        // Idempotenca: external_id + source
        $article = Article::query()->firstOrCreate(
            [
                'external_id' => $this->externalId ?: ($this->title ? Str::slug($this->title) : null),
                'source' => $this->source,
            ],
            [
                'title' => $this->title ?? 'Untitled',
                'content' => $this->content ?? '',
                'published_at' => $this->publishedAt,
            ]
        );

        ProcessArticleJob::dispatch($article->id);

        return response()->json([
            'status' => 'queued',
            'article_id' => $article->id,
        ], 202);
    }
}