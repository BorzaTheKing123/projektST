<?php

namespace App\Domains\TveganjeJobs;

use Illuminate\Support\Facades\Auth;
use App\Models\Tveganje;

class UpdateTveganjeJob
{
    public function __construct(private Tveganje $tveganja, private $request, private $validated)
    {
    }

    public function handle()
    {
        $this->tveganja->update($this->validated);
        $this->tveganja->refresh();

        return response()->json([
            'message' => 'Tveganje uspešno posodobljeno.',
            'data' => $this->tveganja
        ]);
    }
}