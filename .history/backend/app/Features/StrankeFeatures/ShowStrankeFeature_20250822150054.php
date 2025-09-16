<?php

namespace App\Features\StrankeFeatures;
use App\Domains\StrankeJobs\ShowStrankeJob;

class ShowStrankeFeature
{
    public function __construct()
    {
        
    }

    public function handle()
    {
        return new ShowStrankeJob()->handle();
    }
}