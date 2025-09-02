<?php

namespace App\Features\StrankeFeatures;
use App\Domains\StrankeJobs\DeleteStrankaJob;
use App\Models\Stranka;


class DeleteStrankaFeature
{
    public function __construct(private Stranka $stranka)
    {
        
    }

    public function handle()
    {
        return (new DeleteStrankaJob($this->stranka))->handle();
    }
}