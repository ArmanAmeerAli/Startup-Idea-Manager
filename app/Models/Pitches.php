<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pitches extends Model
{
    protected $fillable = [
        'idea_id',
        'pitch_title',
        'pitch_points',
        'pitch_text',
        'status',
    ];

    public function idea()
    {
        return $this->belongsTo(Ideas::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
