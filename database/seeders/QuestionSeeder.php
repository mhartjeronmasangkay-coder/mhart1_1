<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Question;

class QuestionSeeder extends Seeder
{
    public function run(): void
    {
        // Arithmetic - Level 1 (Easy)
        // sub_subject_id: 1, level_id: 1
        $questions = [
            'What is 7 + 5?',
            'What is 9 + 6?',
            'What is 18 − 7?',
            'What is 8 × 3?',
            'What is 20 ÷ 5?',
            'What is 13 + 8?',
            'What is 16 − 9?',
            'What is 7 × 7?',
            'What is 36 ÷ 6?',
            'What is 14 + 9?',
        ];

        foreach ($questions as $question) {
            Question::create([
                'sub_subject_id' => 1,
                'level_id'       => 1,
                'question_text'  => $question,
                'difficulty'     => 'easy',
                'type'           => 'multiple_choice',
                'points'         => 10,
            ]);
        }
    }
}