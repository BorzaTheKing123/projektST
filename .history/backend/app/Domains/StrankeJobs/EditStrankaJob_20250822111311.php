<?php

namespace App\Domains\StrankeJobs;

use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EditStrankaJob
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
        $data = DB::table('stranke')->where('name', $this->stranka)->first();
        return response()->json($data);
        // return Inertia::render('UrejanjeStranke', ['stranka' => $info, 'id' => Auth::id()]);
    }
}