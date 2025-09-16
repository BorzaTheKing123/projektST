<?php

namespace App\Http\Controllers\Api;

use App\Features\HeatmapFeatures\ScrapeFeature;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class ScrapeController extends Controller
{
    /**
     * Executes the Python scraper and returns the JSON output.
     *
     * @return JsonResponse
     */
    public function runScraper(): JsonResponse
    {
        return new ScrapeFeature()->handle();
    }
}