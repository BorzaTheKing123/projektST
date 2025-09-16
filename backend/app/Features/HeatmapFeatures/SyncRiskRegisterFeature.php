<?php

namespace app\Features\HeatmapFeatures;

use App\Models\HeatmapModels\Risk;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

class SyncRiskRegisterFeature
{
    public function __construct(private array $risks) {} // [{name, slug?, category?}, ...]

    public function handle(): JsonResponse
    {
        foreach ($this->risks as $r) {
            Risk::query()->updateOrCreate(
                ['category' => $r['category'] ?? null]
            );
        }

        return response()->json(['status' => 'ok']);
    }
}
