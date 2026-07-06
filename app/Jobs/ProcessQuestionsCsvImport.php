<?php

namespace App\Jobs;

use App\Models\QuestionGroup;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProcessQuestionsCsvImport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 1;

    public function __construct(
        protected string $path,
        protected string $jobId,
        protected int $questionGroupId
    ) {}

    public function handle(): void
    {
        $fullPath = Storage::path($this->path);

        if (! is_file($fullPath)) {
            Cache::put("import:{$this->jobId}", [
                'status' => 'completed',
                'processed' => 0,
                'total' => 0,
                'errors' => ['Import file was missing or already processed.'],
            ], now()->addHour());
            return;
        }

        $questionGroup = QuestionGroup::find($this->questionGroupId);

        if (! $questionGroup) {
            Cache::put("import:{$this->jobId}", [
                'status' => 'completed',
                'processed' => 0,
                'total' => 0,
                'errors' => ['Question group not found.'],
            ], now()->addHour());
            return;
        }

        $handle = fopen($fullPath, 'r');
        fgetcsv($handle); // skip header row
        $rows = [];
        while (($row = fgetcsv($handle)) !== false) {
            $rows[] = $row;
        }
        fclose($handle);

        $total = count($rows);
        $processed = 0;
        $errors = [];

        Cache::put("import:{$this->jobId}", [
            'status' => 'processing', 'processed' => 0, 'total' => $total, 'errors' => [],
        ], now()->addHour());

        foreach ($rows as $i => $row) {
            try {
                $questionText = trim($row[0] ?? '');
                $optionA = trim($row[1] ?? '');
                $optionB = trim($row[2] ?? '');
                $optionC = trim($row[3] ?? '');
                $optionD = trim($row[4] ?? '');
                $correctOption = strtolower(trim($row[5] ?? ''));

                if ($questionText === '') {
                    throw new \Exception('Missing question text');
                }

                $options = ['a' => $optionA, 'b' => $optionB, 'c' => $optionC, 'd' => $optionD];

                foreach ($options as $key => $value) {
                    if ($value === '') {
                        throw new \Exception("Missing option {$key}");
                    }
                }

                if (count(array_unique($options)) !== 4) {
                    throw new \Exception('Options must not be duplicates');
                }

                if (! in_array($correctOption, ['a', 'b', 'c', 'd'], true)) {
                    throw new \Exception('correct_option must be a, b, c, or d');
                }

                DB::transaction(function () use ($questionGroup, $questionText, $options, $correctOption) {
                    $question = $questionGroup->questions()->create([
                        'question_text' => $questionText,
                    ]);

                    $order = 1;
                    foreach ($options as $key => $text) {
                        $question->answers()->create([
                            'answer_text' => $text,
                            'is_correct' => $key === $correctOption,
                            'order' => $order++,
                        ]);
                    }
                });
            } catch (\Throwable $e) {
                $errors[] = 'Row '.($i + 2).': '.$e->getMessage();
            }

            $processed++;
            Cache::put("import:{$this->jobId}", [
                'status' => 'processing', 'processed' => $processed, 'total' => $total, 'errors' => $errors,
            ], now()->addHour());
        }

        Cache::put("import:{$this->jobId}", [
            'status' => 'completed', 'processed' => $processed, 'total' => $total, 'errors' => $errors,
        ], now()->addHour());

        Storage::delete($this->path);
    }
}