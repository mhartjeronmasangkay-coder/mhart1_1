<?php

namespace App\GraphQL\Mutations;

use App\Jobs\ProcessCsvImport;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;

final class ImportSubjectsCsv
{
    public function __invoke($_, array $args)
    {
        /** @var UploadedFile $file */
        $file = $args['file'];

        $path = $file->store('imports');
        $jobId = (string) Str::uuid();

        Cache::put("import:{$jobId}", [
            'status' => 'queued',
            'processed' => 0,
            'total' => 0,
            'errors' => [],
        ], now()->addHour());

        ProcessCsvImport::dispatch($path, $jobId);

        return [
            'jobId' => $jobId,
            'status' => 'queued',
            'processed' => 0,
            'total' => 0,
            'errors' => [],
        ];
    }
}
