<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
     protected $fillable = [
        'sub_subject_id',
        'level_number',
        'name',
        'is_locked'
    ];
        public function question()
    {
        return $this->belongsTo(Question::class);
    }
}
    