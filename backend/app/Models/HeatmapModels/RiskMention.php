<?php
// app/Models/RiskMention.php
namespace App\Models\HeatmapModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RiskMention extends Model
{
    protected $fillable = [
        'article_id', 'risk_id', 'confidence', 'spans'
    ];
    protected $casts = ['spans' => 'array'];

    public function article(): BelongsTo {
        return $this->belongsTo(Article::class);
    }
    public function risk(): BelongsTo {
        return $this->belongsTo(Risk::class);
    }
}
