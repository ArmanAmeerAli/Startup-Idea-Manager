<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ideas extends Model
{

    protected $fillable = [
        'title',
        'description',
        'category',
        'status',
        'ai_score',
        'ai_feedback',
        'user_id',
    ];

    // Relationships

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function validations()
    {
        return $this->hasMany(Validations::class);
    }

    public function pitches()
    {
        return $this->hasMany(Pitches::class);
    }
}
