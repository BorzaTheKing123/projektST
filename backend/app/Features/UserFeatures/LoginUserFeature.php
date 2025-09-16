<?php

namespace App\Features\UserFeatures;
use App\Domains\UserJobs\LoginUserJob;
use App\Domains\UserJobs\RespondWithTokenJob;

class LoginUserFeature
{
    public function handle()
    {   
        return new LoginUserJob()->handle();
    }
}