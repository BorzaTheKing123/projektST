<?php
// app/Models/Article.php
namespace App\Models\HeatmapModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Article extends Model
{
    protected $fillable = [
        'external_id', 'source', 'title', 'content', 'published_at'
    ];
    protected $casts = ['published_at' => 'datetime'];

    public function mentions(): HasMany {
        return $this->hasMany(RiskMention::class);
    }
}
