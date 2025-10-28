<?php

namespace App\Models;

use App\Models\User;
use App\Models\Ideas;
use Illuminate\Database\Eloquent\Model;

class Validations extends Model
{
    protected $fillable = [
        'idea_id',
        'ai_score',
        'ai_feedback',
        'ai_suggestions',
        'ai_model',
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
