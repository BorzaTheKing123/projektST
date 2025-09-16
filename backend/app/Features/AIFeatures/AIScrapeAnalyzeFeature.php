<?php

namespace App\Features\AIFeatures;

use App\Domains\AIJobs\AIresponseJob;
use App\Domains\AIJobs\AIScrapeAnalyzeJob;
use App\Domains\AIJobs\AIScrapePromptJob;

class AIScrapeAnalyzeFeature
{
    public function __construct(private $data)
    {
        //
    }

    public function handle()
    {   
        $prompt = new AIScrapePromptJob($this->data)->handle();
        $content = new AIresponseJob($prompt)->handle();
        return new AIScrapeAnalyzeJob($this->data, $prompt, $content)->handle();
    }
}