<?php

namespace App\Domains\TveganjeJobs;

use Illuminate\Http\Request;

class ValidateTveganjeJob
{
    public function __construct(private Request $request)
    {
    }

    public function handle()
    {
        return $this->request->validate([
            'ime' => 'required|string|max:255',
            'stranka_id' => 'required|exists:stranke,id',
            'ukrepi' => 'required|string'
        ]);
    }
}