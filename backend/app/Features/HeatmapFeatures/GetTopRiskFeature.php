<?php

namespace App\Features\HeatmapFeatures;

use App\Services\RiskService;
use Illuminate\Http\JsonResponse;

class GetTopRisksFeature
{
    protected int $limit;

    public function __construct(int $limit = 10)
    {
        $this->limit = $limit;
    }

    public function handle(): JsonResponse
    {
        $risks = app(RiskService::class)->getTopRisks($this->limit);

        return response()->json($risks);
    }
}
