<?php
// app/Models/RiskMention.php
namespace App\Models\HeatmapModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RiskMention extends Model
{   
    protected $table = 'risk_mentions';

    protected $fillable = [
        'article_id', 'risk_id', 'confidence', 'spans'
    ];

    public function risk(): BelongsTo {
        return $this->belongsTo(Risk::class);
    }
}
