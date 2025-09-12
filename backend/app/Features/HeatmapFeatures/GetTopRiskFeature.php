<?php

namespace App\Features\HeatmapFeatures;

use App\Services\RiskService;
use Illuminate\Http\JsonResponse;

class GetTopRisksFeature
{
    //protected int $limit;

    public function __construct()
    {
        //$this->limit = $limit;
    }

    public function handle(): JsonResponse
    {
        $risks = app(RiskService::class)->getTopRisks();

        return response()->json($risks);
    }
}
