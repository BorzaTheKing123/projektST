<?php
// app/Models/HeatmapModels/RiskMention.php

namespace App\Models\HeatmapModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

class RiskMention extends Model
{
    protected $table = 'risk_mentions';

    protected $fillable = [
        'risk_id', 'confidence', 'link', 'summary'
    ];

    public function risk(): BelongsTo
    {
        return $this->belongsTo(Risk::class);
    }

    // Avtomatsko brisanje starih podatkov ob vsakem dostopu
    public static function booted(): void
    {
        static::retrieved(function () {
            static::where('created_at', '<', Carbon::now()->subWeeks(2))->delete();
        });

        static::creating(function () {
            static::where('created_at', '<', Carbon::now()->subWeeks(2))->delete();
        });
    }
}