<?php

namespace App\Features\StrankeFeatures;
use App\Domains\StrankeJobs\EditStrankaJob;

class EditStrankaFeature
{
    public function __construct(private $stranka)
    {
        
    }

    public function handle()
    {
        return new EditStrankaJob($this->stranka)->handle();
    }
}