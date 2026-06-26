<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Subject;
use App\Models\Question;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    // GET /api/admin/stats
    public function getStats()
    {
        return response()->json([
            'total_students'  => User::where('role', 'student')->count(),
            'total_subjects'  => Subject::count(),
            'total_questions' => Question::count(),
        ]);
    }

    // GET /api/admin/students
    public function getStudents()
    {
        $students = User::where('role', 'student')
            ->select('id', 'name', 'username', 'created_at')
            ->get();
        return response()->json($students);
    }

    // GET /api/admin/subjects
    public function getSubjects()
    {
        return response()->json(Subject::all());
    }

    // POST /api/admin/subjects
    public function createSubject(Request $request)
    {
        $request->validate([
            'name'        => 'required|string',
            'icon'        => 'required|string',
            'description' => 'required|string',
            'color'       => 'required|string',
        ]);

        $subject = Subject::create([
            'name'        => $request->name,
            'icon'        => $request->icon,
            'description' => $request->description,
            'color'       => $request->color,
        ]);

        return response()->json($subject, 201);
    }

    // PUT /api/admin/subjects/{id}
    public function updateSubject(Request $request, $id)
    {
        $subject = Subject::findOrFail($id);
        $subject->update($request->all());
        return response()->json($subject);
    }

    // DELETE /api/admin/subjects/{id}
    public function deleteSubject($id)
    {
        Subject::findOrFail($id)->delete();
        return response()->json(['message' => 'Subject deleted!']);
    }
}