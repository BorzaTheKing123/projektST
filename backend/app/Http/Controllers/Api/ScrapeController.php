<?php

namespace App\Http\Controllers\Api;

use App\Features\HeatmapFeatures\ScrapeFeature;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class ScrapeController extends Controller
{
    public function runScraper(): JsonResponse
    {
        return new ScrapeFeature()->handle();
    }
}