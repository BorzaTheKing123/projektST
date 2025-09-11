<?php
namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Features\HeatmapFeatures\GetTopRisksFeature;
use App\Features\HeatmapFeatures\SyncRiskRegisterFeature;
use App\Features\HeatmapFeatures\AnalyzeArticleFeature;
use App\Http\Controllers\Controller;

class HeatmapController extends Controller
{
    

    public function analyze(Request $request) {
        $data = $request->validate([
            'title' => 'nullable|string',
            'content' => 'required|string',
            'source' => 'nullable|string',
    ]);

        return (new AnalyzeArticleFeature(
          $data['content'],
           $data['title'] ?? null,
           $data['source'] ?? null
        ))->handle();
    }

    public function top(Request $request) {
        $limit = $request->integer('limit', 10);
        return (new GetTopRisksFeature($limit))->handle();
    }

    public function syncRegister(Request $request) {
        $data = $request->validate([
            'risks' => 'required|array',
            'risks.*.category' => 'nullable|string',
        ]);
        return (new SyncRiskRegisterFeature($data['risks']))->handle();
    }
}

