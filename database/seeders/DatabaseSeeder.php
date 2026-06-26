<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            SubjectSeeder::class,
            SubSubjectSeeder::class,
            LevelSeeder::class,
            QuestionSeeder::class,
            AnswerSeeder::class,
        ]);
    }
}