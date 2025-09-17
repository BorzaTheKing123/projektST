<?php

namespace App\Domains\UserJobs;

use App\Models\User;

class StoreNewUserJob
{
    public function __construct(private $credentials)
    {
        
    }

    public function handle()
    {
        // $user = User::create([
        //             'name' => $this->credentials['name'],
        //             'email' => $this->credentials['email'],
        //             'password' => Hash::make($this->credentials['password']),
        //         ]);

        $user = User::create($this->credentials);
        return response()->json($user);
    }
}