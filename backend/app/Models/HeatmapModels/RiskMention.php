<?php
// app/Models/RiskMention.php
namespace App\Models\HeatmapModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RiskMention extends Model
{   
    protected $table = 'risk_mentions';

    protected $fillable = [
        'risk_id', 'confidence', 'link', 'summary'
    ];

    public function risk(): BelongsTo {
        return $this->belongsTo(Risk::class);
    }

    protected static function booted()
    {
        // Ko je članek ustvarjen
        static::created(function ($risk) {
            // Povečaj števec na povezani ključni besedi
            if ($risk->risk_id) {
                Risk::find($risk->risk_id)->increment('article_count');
            }
        });

        // Ko je članek izbrisan
        static::deleted(function ($risk) {
            // Zmanjšaj števec na povezani ključni besedi
            if ($risk->risk_id) {
                Risk::find($risk->risk_id)->decrement('article_count');
            }
        });
        
        // POZOR: Dodatno logiko bi potreboval tudi za "updated", 
        // če bi članek lahko zamenjal ključno besedo!
    }
}
