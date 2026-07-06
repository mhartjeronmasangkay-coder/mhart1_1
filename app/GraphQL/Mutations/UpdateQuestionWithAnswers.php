<?php

namespace App\GraphQL\Mutations;

use App\Models\Question;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

final class UpdateQuestionWithAnswers
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
            $question = Question::findOrFail($args['id']);
            $question->update(['question_text' => $args['question_text']]);

            // Replace all answers cleanly (simplest, avoids id-matching edge cases)
            $question->answers()->delete();
            $question->answers()->createMany(array_map(fn($a) => [
                'answer_text' => $a['answer_text'],
                'is_correct'  => $a['is_correct'],
                'order'       => $a['order'],
            ], $answers));

            return $question->load('answers');
        });
    }
}