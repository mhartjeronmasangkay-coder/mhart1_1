<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Level extends Model
{
    protected $fillable = [
        'sub_subject_id',
        'level_number',
        'name',
        'is_locked'
    ];

    public function subSubject()
    {
        return $this->belongsTo(SubSubject::class);
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }
}