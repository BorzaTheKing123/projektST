<?php

namespace App\Features\UserFeatures;

use App\Domains\UserJobs\StoreNewUserJob;
use App\Domains\UserJobs\UserInfoValidationJob;

class RegisterUserFeature
{
    public function __construct(private $request)
    {
        
    }
    public function handle()
    {
        $credentials = new UserInfoValidationJob($this->request)->handle();
        return new StoreNewUserJob($credentials, $this->request)->handle();
    }
}