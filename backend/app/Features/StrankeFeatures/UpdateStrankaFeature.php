<?php

namespace App\Features\StrankeFeatures;

use App\Domains\StrankeJobs\UpdateStrankaJob;
use App\Domains\StrankeJobs\ValidateStrankaJob;

class UpdateStrankaFeature
{
    public function __construct(private $stranka, private $request)
    {
    }

    public function handle()
    {
        // Validacija z dostopom do stranke (zaradi email izjeme)
        $info = (new ValidateStrankaJob($this->request, $this->stranka))->handle();

        // Posodobitev stranke
        return (new UpdateStrankaJob($this->stranka, $this->request, $info))->handle();
    }
}
