<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stranka extends Model
{
    protected $table = 'stranke';

    protected $fillable = [
        'user_id',
        'name',
        'email',
        'phone',
        'dejavnost'
    ];
    public function tveganja()
    {
        return $this->hasMany(Tveganje::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }


}
