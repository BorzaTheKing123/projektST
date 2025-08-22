<?php

namespace App\Features\StrankeFeatures;
use App\Domains\StrankeJobs\DeleteStrankaJob;

class DeleteStrankaFeature
{
    public function __construct(private $stranka)
    {
        
    }

    public function handle()
    {
        return new DeleteStrankaJob($this->stranka)->handle();
    }
}