<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    // Get all subjects with their sub_subjects
    public function index()
    {
        $subjects = Subject::with('subSubjects')->get();
        return response()->json($subjects);
    }
}