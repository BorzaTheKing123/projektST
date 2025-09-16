<?php
namespace App\Http\Controllers\Api;

use App\Features\HeatmapFeatures\GetTopRisksFeature;
use App\Http\Controllers\Controller;

class HeatmapController extends Controller
{
    public function top() {
        return new GetTopRisksFeature()->handle();
    }
}

