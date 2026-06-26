<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Level;

class LevelSeeder extends Seeder
{
    public function run(): void
    {
        $levelNames = [
            1 => ['name' => 'Easy',           'is_locked' => false],
            2 => ['name' => 'Quite Easy',     'is_locked' => true],
            3 => ['name' => 'Troublesome',    'is_locked' => true],
            4 => ['name' => 'Hard',           'is_locked' => true],
            5 => ['name' => 'Extremely Hard', 'is_locked' => true],
        ];

        // First sub_subject of each subject (unlocked)
        $firstSubjects = [1, 7, 12];

        for ($subSubjectId = 1; $subSubjectId <= 16; $subSubjectId++) {
            foreach ($levelNames as $levelNumber => $level) {

                // Only Level 1 of first sub_subjects are unlocked
                $isLocked = true;
                if (in_array($subSubjectId, $firstSubjects) && $levelNumber === 1) {
                    $isLocked = false;
                }

                Level::create([
                    'sub_subject_id' => $subSubjectId,
                    'level_number'   => $levelNumber,
                    'name'           => $level['name'],
                    'is_locked'      => $isLocked,
                ]);
            }
        }
    }
}