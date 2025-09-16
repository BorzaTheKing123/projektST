<?php

namespace App\Features\TveganjeFeatures;
use App\Domains\TveganjeJobs\ShowTveganjeJob;

class ShowTveganjeFeature
{
    public function __construct()
    {
        
    }

    public function handle()
    {
        return new ShowTveganjeJob()->handle();
    }
}