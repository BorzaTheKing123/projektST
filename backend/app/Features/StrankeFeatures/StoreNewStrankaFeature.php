<?php

namespace App\Features\StrankeFeatures;

use App\Domains\StrankeJobs\StoreNewStrankaJob;
use App\Domains\StrankeJobs\ValidateStrankaJob;

class StoreNewStrankaFeature
{
    public function __construct(private $request)
    {
        
    }

    public function handle()
    {
        $input = new ValidateStrankaJob($this->request)->handle();
        return new StoreNewStrankaJob($this->request, $input)->handle();
    }
}