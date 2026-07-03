<?php

namespace App\GraphQL\Queries;

use Illuminate\Support\Facades\Cache;

final class ImportStatus
{
    public function __invoke($_, array $args)
    {
        $jobId = $args['jobId'];

        $data = Cache::get("import:{$jobId}", [
            'status' => 'not_found',
            'processed' => 0,
            'total' => 0,
            'errors' => [],
        ]);

        return array_merge($data, ['jobId' => $jobId]);
    }
}
