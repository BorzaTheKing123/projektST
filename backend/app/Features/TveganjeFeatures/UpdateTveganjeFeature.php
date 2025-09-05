<?php

namespace App\Features\TveganjeFeatures;

use App\Domains\TveganjeJobs\UpdateTveganjeJob;
use App\Domains\TveganjeJobs\ValidateTveganjeJob;

class UpdateTveganjeFeature
{
    public function __construct(private $tveganja, private $request)
    {
    }

    public function handle()
    {
        // Validacija z dostopom do stranke (zaradi email izjeme)
        $info = (new ValidateTveganjeJob($this->request, $this->tveganja))->handle();

        // Posodobitev stranke
        return (new UpdateTveganjeJob($this->tveganja, $this->request, $info))->handle();
    }
}
