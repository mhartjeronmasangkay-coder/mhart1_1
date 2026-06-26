<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = [
        'sub_subject_id',
        'question_text',
        'difficulty',
        'type',
        'level_id',
        'points'
    ];

    public function level()
    {
        return $this->belongsTo(Level::class);
    }

    public function answers()
    {
        return $this->hasMany(Answer::class);
    }
}