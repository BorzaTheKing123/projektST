<?php

namespace App\Features\HeatmapFeatures;

use App\Domains\HeatmapJobs\Scrapejob;
use Illuminate\Http\JsonResponse;

class ScrapeFeature
{
    public function handle(): JsonResponse
    {
        return new Scrapejob()->handle();
    }
}
