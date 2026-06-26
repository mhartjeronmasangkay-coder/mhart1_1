<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubSubject extends Model
{
    protected $fillable = [
        'name',
        'subject_id',
        'description',
        'level_required',
        'is_locked'
    ];
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

        public function levels()
    {
        return $this->hasMany(Level::class);
    }
}