<?php

namespace App\Features\StrankeFeatures;
use App\Domains\StrankeJobs\ShowStrankeJob;

class ShowStrankeFeature
{
    public function __construct(private $id)
    {
        
    }

    public function handle()
    {
        return new ShowStrankeJob($this->id)->handle();
    }
}