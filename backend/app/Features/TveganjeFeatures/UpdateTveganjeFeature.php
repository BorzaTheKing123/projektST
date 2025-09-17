<?php

namespace App\Features\TveganjeFeatures;

use App\Domains\TveganjeJobs\GetStrankaJob;
use App\Domains\TveganjeJobs\UpdateTveganjeJob;
use App\Domains\TveganjeJobs\ValidateTveganjeJob;

class UpdateTveganjeFeature
{
    public function __construct(private $request, private $tveganja)
    {
    }

    public function handle()
    {
        // Validacija z dostopom do stranke (zaradi email izjeme)
        $validated = new ValidateTveganjeJob($this->request)->handle();
        new GetStrankaJob($this->request, $validated)->handle();
        return new UpdateTveganjeJob($this->tveganja, $this->request, $validated)->handle();
    }
}
