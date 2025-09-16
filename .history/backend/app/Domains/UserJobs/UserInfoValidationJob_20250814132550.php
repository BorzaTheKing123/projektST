<?php

namespace App\Domains\UserJobs;

class UserInfoValidationJob
{

    protected $userData;

    public function __construct(private $request)
    {

    }

    public function handle()
    {
        $credentials = $this->request->validate([
            'name' => ['required', 'string', 'alpha_dash', 'min:3', 'max:255', 'unique:users,username'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'regex:/[a-z]/', 'regex:/[A-Z]/', 'regex:/[0-9]/', 
            'regex:/[@$!%*#?&]/',]
        ]);

        return $credentials;
    }
}