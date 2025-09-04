<?php

namespace App\Domains\TveganjeJobs;

use Illuminate\Support\Facades\DB;

class EditTveganjeJob
{
    public function __construct(private $tveganje)
    {
        
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $data = DB::table('tveganje')->where('name', $this->tveganje)->first();
        return response()->json($data);
    }
}