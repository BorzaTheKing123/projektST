<?php

namespace App\Domains\UserJobs;

use App\Models\User;
use Inertia\Inertia;

class StoreNewUserJob
{
    public function __construct(private $credentials, private $request)
    {
        
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        User::create($this->credentials);
        $this->request->session()->regenerate();

        // Token za uporabnika iz njegovega uporabniškega imena itd
        return true;
    }
}