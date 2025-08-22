<?php

namespace App\Domains\StrankeJobs;

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
        return response()->json($stranke);;
    }
        
}