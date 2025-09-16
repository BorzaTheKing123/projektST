<?php

namespace App\Http\Controllers\Api;

use App\Features\AIFeatures\AIUkrepFeature;
use App\Features\AIFeatures\AIScrapeAnalyzeFeature;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AiController extends Controller
{
    public function predlogi(Request $request)
    {   
        return new AIUkrepFeature($request)->handle();
    }

    public function article($data)
    {
        return new AIScrapeAnalyzeFeature($data)->handle();
    }
}