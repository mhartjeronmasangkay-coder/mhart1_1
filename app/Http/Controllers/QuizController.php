<?php

namespace App\Http\Controllers;

use App\Models\Level;
use App\Models\Subject;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    // Get all subjects with sub_subjects and levels
    public function getSubjects()
    {
        $subjects = Subject::with('subSubjects.levels')->get();
        return response()->json($subjects);
    }

    // Get questions for a specific level
  public function getQuestions(int $levelId)
    {
        $level = Level::with('questions.answers')->findOrFail($levelId);
        return response()->json($level);
    }
}