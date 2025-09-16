<?php

namespace App\Features\AIFeatures;

use App\Domains\AIJobs\AIresponseJob;
use App\Domains\AIJobs\AIUkrepJob;
use App\Domains\AIJobs\AIUkrepPromptJob;

class AIUkrepFeature
{
    public function __construct(private $request)
    {
        //
    }

    public function handle()
    {
        $prompt = new AIUkrepPromptJob($this->request)->handle();
        $response = new AIresponseJob($prompt)->handle();
        return new AIUkrepJob($this->request, $response)->handle();
    }
}