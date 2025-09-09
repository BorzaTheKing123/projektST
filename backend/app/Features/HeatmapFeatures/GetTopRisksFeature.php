<?php

// app/Features/GetTopRisksFeature.php
namespace App\Features\HeatmapFeatures;

use App\Services\RiskService;
use Illuminate\Http\JsonResponse;

class GetTopRisksFeature
{
    public function __construct(private int $limit = 10) {}

    public function handle(): JsonResponse
    {
        $data = app(RiskService::class)->getTopRisks($this->limit);
        return response()->json($data);
    }
}