<?php
// app/Features/AnalyzeArticleFeature.php
namespace App\Features\HeatmapFeatures;

use App\Services\LLMService;
use Illuminate\Http\JsonResponse;

class AnalyzeArticleFeature
{
    public function __construct(
        private string $content,
        private ?string $title = null,
        private ?string $source = null,
    ) {}

    public function handle(): JsonResponse
    {
        $llm = app(LLMService::class);
        $risks = $llm->analyzeArticle($this->content);

        return response()->json([
            'title' => $this->title,
            'source' => $this->source,
            'risks' => $risks,
        ]);
    }
}
