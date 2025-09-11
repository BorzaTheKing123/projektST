<?php
// app/Models/Risk.php
namespace App\Models\HeatmapModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Risk extends Model
{
    protected $table = 'risks';

    protected $fillable = [
        'category', 'article_count', 'opis',
    ];

    public function mentions(): HasMany {
        return $this->hasMany(RiskMention::class);
    }
}