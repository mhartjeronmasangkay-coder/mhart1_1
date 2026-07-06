<?php

namespace App\GraphQL\Mutations;

use App\Models\Question;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

final class CreateQuestionWithAnswers
{
    public function __invoke($_, array $args)
    {
        $answers = $args['answers'];

        if (count($answers) !== 4) {
            throw ValidationException::withMessages([
                'answers' => 'Exactly 4 answers are required.',
            ]);
        }

        $correctCount = collect($answers)->where('is_correct', true)->count();

        if ($correctCount !== 1) {
            throw ValidationException::withMessages([
                'answers' => 'Exactly one answer must be marked correct.',
            ]);
        }

        return DB::transaction(function () use ($args, $answers) {
            $question = Question::create([
                'question_group_id' => $args['question_group_id'],
                'question_text' => $args['question_text'],
            ]);

            $question->answers()->createMany($answers);

            return $question->load('answers');
        });
    }
}