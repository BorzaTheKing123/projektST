<?php

namespace App\Features\AIFeatures;

use App\Domains\AIJobs\AIresponseJob;
use App\Domains\AIJobs\AIUkrepJob;
use App\Domains\AIJobs\AIUkrepPromptJob;
use Illuminate\Support\Facades\Log;

class AIUkrepFeature
{
    public function __construct(private $request)
    {
        //
    }

    public function handle()
    {
        $prompt = new AIUkrepPromptJob($this->request)->handle();
        Log::info($prompt);
        $response = new AIresponseJob($prompt)->handle();
        return new AIUkrepJob($this->request, $response)->handle();
    }
}