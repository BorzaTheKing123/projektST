<?php

namespace App\Features\StrankeFeatures;

use App\Domains\StrankeJobs\StoreNewStrankaJob;
use App\Domains\StrankeJobs\ValidateStrankaJob;

class StoreNewStrankaFeature
{
    public function __construct(private $request)
    {
    }

    public function handle()
    {
        // 🔍 Validiraj vhodne podatke
        $input = (new ValidateStrankaJob($this->request))->handle();

        // 🔐 Dodaj user_id iz prijavljenega uporabnika
        $input['user_id'] = $this->request->user()->id;

        // 💾 Shrani stranko z vsemi podatki
        return (new StoreNewStrankaJob($this->request, $input))->handle();
    }
}
