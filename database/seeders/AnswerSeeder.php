<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Answer;

class AnswerSeeder extends Seeder
{
    public function run(): void
    {
        $answers = [
            // Q1: What is 7 + 5? (question_id: 1)
            [1, '10', false],
            [1, '11', false],
            [1, '12', true],
            [1, '13', false],

            // Q2: What is 9 + 6? (question_id: 2)
            [2, '14', false],
            [2, '15', true],
            [2, '16', false],
            [2, '17', false],

            // Q3: What is 18 - 7? (question_id: 3)
            [3, '9',  false],
            [3, '10', false],
            [3, '11', true],
            [3, '12', false],

            // Q4: What is 8 x 3? (question_id: 4)
            [4, '21', false],
            [4, '22', false],
            [4, '24', true],
            [4, '26', false],

            // Q5: What is 20 ÷ 5? (question_id: 5)
            [5, '2', false],
            [5, '3', false],
            [5, '4', true],
            [5, '5', false],

            // Q6: What is 13 + 8? (question_id: 6)
            [6, '20', false],
            [6, '21', true],
            [6, '22', false],
            [6, '23', false],

            // Q7: What is 16 - 9? (question_id: 7)
            [7, '6', false],
            [7, '7', true],
            [7, '8', false],
            [7, '9', false],

            // Q8: What is 7 x 7? (question_id: 8)
            [8, '42', false],
            [8, '47', false],
            [8, '48', false],
            [8, '49', true],

            // Q9: What is 36 ÷ 6? (question_id: 9)
            [9, '5', false],
            [9, '6', true],
            [9, '7', false],
            [9, '8', false],

            // Q10: What is 14 + 9? (question_id: 10)
            [10, '22', false],
            [10, '23', true],
            [10, '24', false],
            [10, '25', false],
        ];

        foreach ($answers as $answer) {
            Answer::create([
                'question_id' => $answer[0],
                'answer_text' => $answer[1],
                'is_correct'  => $answer[2],
            ]);
        }
    }
}