<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessCsvImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class ImportController extends Controller
{
    public function import(Request $request)
    {
        $request->validate(['csv' => 'required|file|mimes:csv,txt']);

        $file = $request->file('csv');
        $tempPath = tempnam(sys_get_temp_dir(), 'csv-import-');

        if ($tempPath === false) {
            return response()->json(['message' => 'The uploaded file could not be stored.'], 500);
        }

        $file->move(dirname($tempPath), basename($tempPath));
        $path = $tempPath;

        $jobId = (string) Str::uuid();

        Cache::put("import:{$jobId}", [
            'status' => 'queued',
            'processed' => 0,
            'total' => 0,
            'errors' => [],
        ], now()->addHour());

        $job = new ProcessCsvImport($path, $jobId);
        $job->handle();

        $result = Cache::get("import:{$jobId}", [
            'status' => 'completed',
            'processed' => 0,
            'total' => 0,
            'errors' => [],
        ]);

        return response()->json(array_merge(['job_id' => $jobId], $result));
    }

    public function status(string $jobId)
    {
        return response()->json(Cache::get("import:{$jobId}", [
            'status' => 'not_found',
            'processed' => 0,
            'total' => 0,
            'errors' => [],
        ]));
    }
}
