<?php

namespace App\GraphQL\Mutations;

use App\Jobs\ProcessQuestionsCsvImport;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;

final class ImportQuestionsCsv
{
    public function __invoke($_, array $args)
    {
        /** @var UploadedFile $file */
        $file = $args['file'];
        $questionGroupId = (int) $args['question_group_id'];

        $path = $file->store('imports');
        $jobId = (string) Str::uuid();

        Cache::put("import:{$jobId}", [
            'status' => 'queued',
            'processed' => 0,
            'total' => 0,
            'errors' => [],
        ], now()->addHour());

        ProcessQuestionsCsvImport::dispatch($path, $jobId, $questionGroupId);

        return [
            'jobId' => $jobId,
            'status' => 'queued',
            'processed' => 0,
            'total' => 0,
            'errors' => [],
        ];
    }
}