<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $fillable = [
        'name',
        'description',
        'icon',
        'color',
        'is_published',
    ];

    protected $casts = [
        'is_published' => 'boolean',
    ];
}
