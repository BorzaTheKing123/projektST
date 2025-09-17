<?php

namespace App\Features\TveganjeFeatures;

use App\Domains\TveganjeJobs\StoreNewTveganjeJob;
use App\Domains\TveganjeJobs\ValidateTveganjeJob;

class StoreNewTveganjeFeature
{
    public function __construct(private $request)
    {
        
    }

    public function handle()
    {
        $validated = new ValidateTveganjeJob($this->request)->handle();
        return new StoreNewTveganjeJob($validated)->handle();
    }
}