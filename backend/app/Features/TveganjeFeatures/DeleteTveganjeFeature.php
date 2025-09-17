<?php

namespace App\Features\TveganjeFeatures;
use App\Domains\TveganjeJobs\DeleteTveganjeJob;


class DeleteTveganjeFeature
{
    public function __construct(private $tveganje)
    {
        
    }

    public function handle()
    {
        return new DeleteTveganjeJob($this->tveganje)->handle();
    }
}