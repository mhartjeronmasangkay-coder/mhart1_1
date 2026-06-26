<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $fillable = [
        'name',
        'icon',
        'color',
        'description'
    ];
    public function subSubjects()
{
    return $this->hasMany(SubSubject::class);
}
}