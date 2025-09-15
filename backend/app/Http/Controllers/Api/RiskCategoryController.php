<?php

namespace App\Http\Controllers\Api;

use App\Models\HeatmapModels\Risk;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RiskCategoryController extends Controller
{
    public function show($id)
    {
        $risk = Risk::with('mentions')->findOrFail($id);

        $articles = $risk->mentions->map(function ($mention) {
            return [
                'title' => $mention->summary,
                'url' => $mention->link,
                'intensity' => round($mention->confidence * 100), // za heatmap
            ];
        });

        return response()->json([
            'category' => $risk->category,
            'article_count' => $articles->count(),
            'articles' => $articles,
            'summary' => $risk->opis,
        ]);
    }
}