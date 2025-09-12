<?php

namespace App\Models\HeatmapModels;

use Illuminate\Database\Eloquent\Model;

class Risk extends Model
{
    protected $fillable = ['category', 'opis', 'article_count'];

    public function mentions()
    {
        return $this->hasMany(RiskMention::class);
    }
}