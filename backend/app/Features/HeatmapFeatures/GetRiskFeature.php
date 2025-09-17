<?php

namespace App\Features\HeatmapFeatures;

use App\Domains\HeatmapJobs\GetRiskJob;
use App\Domains\HeatmapJobs\GetArticleInfoJob;
use Illuminate\Http\JsonResponse;

class GetRiskFeature
{
    public function __construct(private $id)
    {
        
    }

    public function handle(): JsonResponse
    {
        $risk = new GetRiskJob($this->id)->handle();
        return new GetArticleInfoJob($risk)->handle();
    }
}