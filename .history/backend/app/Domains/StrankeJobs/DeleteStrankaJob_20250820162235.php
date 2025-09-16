<?php

namespace App\Domains\StrankeJobs;

use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DeleteStrankaJob
{
    public function __construct(private $stranka)
    {
        
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        DB::table('stranke')->where('name', $this->stranka)->where('user_id', Auth::id())->delete();
        return Inertia::render('Stranke', ['id' => Auth::id()]);
    }
}