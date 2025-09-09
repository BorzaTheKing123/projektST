
<?php

namespace App\Features\HeatmapFeatures;// app/Features/SyncRiskRegisterFeature.php


use App\Models\HeatmapModels\Risk;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

class SyncRiskRegisterFeature
{
    public function __construct(private array $risks) {} // [{name, slug?, category?}, ...]

    public function handle(): JsonResponse
    {
        foreach ($this->risks as $r) {
            $slug = $r['slug'] ?? Str::slug(mb_strtolower($r['name']));
            Risk::query()->updateOrCreate(
                ['slug' => $slug],
                ['name' => $r['name'], 'category' => $r['category'] ?? null]
            );
        }

        return response()->json(['status' => 'ok']);
    }
}
