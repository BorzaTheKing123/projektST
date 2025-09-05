<?php

namespace App\Domains\TveganjeJobs;

use Illuminate\Support\Facades\DB;

class EditTveganjeJob
{
    public function __construct(private $tveganja)
    {
        
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $data = DB::table('tveganja')->where('name', $this->tveganja)->first();
        return response()->json($data);
    }
}