<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExportController extends Controller
{
    public function export(): StreamedResponse
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->fromArray(['Name', 'Description', 'Created At'], null, 'A1');

        $subjects = Subject::all(['name', 'description', 'created_at']);
        $row = 2;
        foreach ($subjects as $s) {
            $sheet->fromArray([$s->name, $s->description, $s->created_at?->format('Y-m-d')], null, "A{$row}");
            $row++;
        }

        return response()->streamDownload(function () use ($spreadsheet) {
            (new Xlsx($spreadsheet))->save('php://output');
        }, 'subjects.xlsx', [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }
}
