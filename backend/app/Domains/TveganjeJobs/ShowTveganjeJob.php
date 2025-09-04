<?php

namespace App\Domains\TveganjeJobs;

use Illuminate\Support\Facades\DB;

class ShowTveganjeJob
{
    public function __construct()
    {
        
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $tveganje = DB::table('tveganje')->get();
        return response()->json($tveganje);;
    }
        
}