<?php

namespace App\Domains\HeatmapJobs;

class GetArticleInfoJob
{
    public function __construct(private $risk)
    {

    }

    public function handle()
    {
        $articles = $this->risk->mentions->map(function ($mention) {
            return [
                'title' => $mention->summary,
                'url' => $mention->link,
                'intensity' => round($mention->confidence * 100), // za heatmap
            ];
        });

        return response()->json([
            'category' => $this->risk->category,
            'article_count' => $articles->count(),
            'articles' => $articles,
            'summary' => $this->risk->opis,
        ]);
    }
}