<?php
// app/Models/Risk.php
namespace App\Models\HeatmapModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Risk extends Model
{
    protected $fillable = [
        'slug', 'name', 'category', 'mention_count', 'article_count', 'metadata'
    ];
    protected $casts = ['metadata' => 'array'];

    public function mentions(): HasMany {
        return $this->hasMany(RiskMention::class);
    }
}