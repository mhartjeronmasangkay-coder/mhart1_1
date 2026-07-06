<?php

namespace App\Http\Controllers;

use App\Models\QuestionGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class PdfController extends Controller
{
    public function generate(Request $request)
    {
        $validated = $request->validate([
            'question_group_id' => 'required|integer|exists:question_groups,id',
            'show_answers'      => 'boolean',
        ]);

        $showAnswers = $validated['show_answers'] ?? false;

        /** @var QuestionGroup $group */
        $group = QuestionGroup::with(['questions.answers'])->findOrFail($validated['question_group_id']);

        if ($group->questions->isEmpty()) {
            return response()->json(['error' => 'No questions found in this folder.'], 400);
        }

        $subjectName = optional($group->subject)->name ?? 'Quiz';

        $data = [
            'requester_name'      => $request->user()->name,
            'requester_role'      => ucfirst($request->user()->role),
            'generated_date'      => now()->format('F j, Y'),
            'institution_name'    => 'Bestlink College of the Philippines',
            'subject_name'        => $subjectName,
            'question_group_name' => $group->name,
            'file_folder'         => $group->name,
            'show_answers'        => $showAnswers,
            'questions'           => $group->questions->map(function ($q) {
                return [
                    'question_text' => $q->question_text,
                    'answers'       => $q->answers->sortBy('order')->values()->map(function ($a) {
                        return [
                            'answer_text' => $a->answer_text,
                            'is_correct'  => (bool) $a->is_correct,
                        ];
                    })->toArray(),
                ];
            })->toArray(),
        ];

        try {
            $pdfPath = $this->compileTypst($data, $group->name);

            $filename = preg_replace('/[^A-Za-z0-9_\-]/', '_', $group->name) . '_Quiz.pdf';

            return response()->download($pdfPath, $filename, [
                'Content-Type' => 'application/pdf',
            ])->deleteFileAfterSend(true);

        } catch (\Throwable $e) {
            Log::error('PDF generation failed: ' . $e->getMessage());
            return response()->json(['error' => 'PDF generation failed: ' . $e->getMessage()], 500);
        }
    }

    private function compileTypst(array $data, string $groupName): string
    {
        $tmpDir = storage_path('app/typst_tmp');

        if (! is_dir($tmpDir)) {
            mkdir($tmpDir, 0755, true);
        }

        $typstDir  = resource_path('typst');
        $slug      = preg_replace('/[^A-Za-z0-9_\-]/', '_', $groupName);
        $timestamp = time();

        $jsonFilename = "tmp_{$slug}_{$timestamp}.json";
        $jsonFile     = "{$typstDir}/{$jsonFilename}";
        $outputPdf    = "{$tmpDir}/{$slug}_{$timestamp}.pdf";

        file_put_contents($jsonFile, json_encode($data));

        $templatePath = resource_path('typst/testpaper.typ');

        if (! file_exists($templatePath)) {
            throw new \RuntimeException("Typst template not found at: {$templatePath}");
        }

        $process = new Process([
            'typst',
            'compile',
            '--input', "data={$jsonFilename}",
            $templatePath,
            $outputPdf,
        ]);
        $process->setWorkingDirectory($typstDir);
        $process->setTimeout(60);
        $process->run();

        @unlink($jsonFile);

        if (! $process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        return $outputPdf;
    }
}
