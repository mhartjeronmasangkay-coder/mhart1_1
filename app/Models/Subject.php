<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    public function questionGroups(): HasMany
    {
        return $this->hasMany(QuestionGroup::class);
    }
}