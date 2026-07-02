<?php

namespace Tests\Feature;

use App\Models\Subject;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ImportControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_import_processes_csv_inline_and_persists_subjects(): void
    {
        Storage::fake('local');

        $csvPath = tempnam(sys_get_temp_dir(), 'subjects');
        file_put_contents($csvPath, "name,description\nPhysics,Study of matter\nChemistry,Study of reactions\n");

        $file = new UploadedFile($csvPath, 'subjects.csv', 'text/csv', null, true);

        $response = $this->postJson('/api/import-users', ['csv' => $file]);

        $response->assertOk();
        $response->assertJsonPath('status', 'completed');
        $response->assertJsonPath('processed', 2);
        $response->assertJsonPath('total', 2);

        $this->assertDatabaseHas('subjects', ['name' => 'Physics']);
        $this->assertDatabaseHas('subjects', ['name' => 'Chemistry']);

        $this->assertSame(2, Subject::count());
    }
}
