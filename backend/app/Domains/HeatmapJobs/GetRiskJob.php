<?php

namespace App\Domains\HeatmapJobs;
use App\Models\HeatmapModels\Risk;

class GetRiskJob
{
    public function __construct(private $id)
    {

    }

    public function handle()
    {
        return Risk::with('mentions')->findOrFail($this->id);
    }
}