<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Stranka;

class Tveganje extends Model
{
    protected $table = 'tveganja'; // ime tabele v množini

    protected $fillable = [
        'ime',         // ime tveganja
        'stranka_id',  // FK do stranke
        'ukrepi'       // opis ukrepov
    ];

    // Relacija: tveganja pripada eni stranki
    public function stranka()
    {
        return $this->belongsTo(Stranka::class);
    }
}