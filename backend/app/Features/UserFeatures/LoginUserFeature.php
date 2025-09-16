<?php

namespace App\Features\UserFeatures;
use App\Domains\UserJobs\LoginUserJob;

class LoginUserFeature
{
    public function __construct(private $request)
    {
        
    }

    public function handle()
    {
        return new LoginUserJob($this->request)->handle();
    }
}