<?php

namespace App\Features\TveganjeFeatures;
use App\Domains\TveganjeJobs\EditTveganjeJob;

class EditTveganjeFeature
{
    public function __construct(private $stranka)
    {
        
    }

    public function handle()
    {
        return new EditTveganjeJob($this->stranka)->handle();
    }
}