<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Subject;

class SubjectSeeder extends Seeder
{
    public function run(): void
    {
        Subject::create([
            'name' => 'Mathematics',
            'icon' => '📐',
            'color' => '#3b82f6',
            'description' => 'Explore numbers, algebra, geometry and more!'
        ]);

        Subject::create([
            'name' => 'Science',
            'icon' => '🔬',
            'color' => '#10b981',
            'description' => 'Discover biology, chemistry, physics and more!'
        ]);

        Subject::create([
            'name' => 'English',
            'icon' => '📖',
            'color' => '#f59e0b',
            'description' => 'Master grammar, vocabulary, reading and writing!'
        ]);
    }
}