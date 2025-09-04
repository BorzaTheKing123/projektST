<?php

namespace App\Features\TveganjeFeatures;
use App\Domains\TveganjeJobs\DeleteTveganjeJob;
use App\Models\Tveganje;


class DeleteTveganjeFeature
{
    public function __construct(private Tveganje $stranka)
    {
        
    }

    public function handle()
    {
        return (new DeleteTveganjeJob($this->stranka))->handle();
    }
}