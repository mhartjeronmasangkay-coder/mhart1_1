<?php

namespace App\Jobs;

use App\Models\Subject;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class ProcessCsvImport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 1;

    public function __construct(
        protected string $path,
        protected string $jobId
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

        $handle = fopen($fullPath, 'r');
        $header = fgetcsv($handle);

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
                $name = trim($row[0] ?? '');
                $description = trim($row[1] ?? '');

                if ($name === '') {
                    throw new \Exception('Missing subject name');
                }

                Subject::updateOrCreate(
                    ['name' => $name],
                    ['description' => $description]
                );
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
