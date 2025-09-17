<?php

namespace App\Domains\TveganjeJobs;

use App\Models\Stranka;

class GetStrankaJob
{
    public function __construct(private $request, private $validated)
    {
    }

    public function handle()
    {
        $user = $this->request->user();
        $stranka = Stranka::where('id', $this->validated['stranka_id'])
            ->where('user_id', $user->id)
            ->first();

        if (!$stranka) {
            return response()->json([
                'message' => 'Ne moreš posodobiti tveganja za stranko, ki ni tvoja.'
            ], 403);
        }
    }
}