<?php

namespace App\Http\Controllers\Api;

use App\Features\HeatmapFeatures\GetRiskFeature;
use App\Http\Controllers\Controller;

class RiskCategoryController extends Controller
{
    public function show($id)
    {
        return new GetRiskFeature($id)->handle();
    }
}