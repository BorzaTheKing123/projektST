<?php

namespace App\Domains\StrankeJobs;

use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ShowStrankeJob
{
    public function __construct(private $id)
    {
        
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $stranke = DB::table('stranke')->get();
        return Inertia::render('Stranke', ['id' => $this->id, 'stranke' => $stranke]);
    }
        
}