<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SubSubject;

class SubSubjectSeeder extends Seeder
{
    public function run(): void
    {
        // Mathematics (subject_id: 1)
        $mathSubs = [
            ['name' => 'Arithmetic',               'description' => 'Basic numbers and operations',  'is_locked' => false],
            ['name' => 'Algebra',                  'description' => 'Equations and variables',        'is_locked' => true],
            ['name' => 'Geometry',                 'description' => 'Shapes and spaces',              'is_locked' => true],
            ['name' => 'Trigonometry',             'description' => 'Angles and triangles',           'is_locked' => true],
            ['name' => 'Calculus',                 'description' => 'Derivatives and integrals',      'is_locked' => true],
            ['name' => 'Statistics & Probability', 'description' => 'Data and chance',                'is_locked' => true],
        ];

        foreach ($mathSubs as $sub) {
            SubSubject::create([
                'subject_id'     => 1,
                'name'           => $sub['name'],
                'description'    => $sub['description'],
                'level_required' => 1,
                'is_locked'      => $sub['is_locked'],
            ]);
        }

        // Science (subject_id: 2)
        $scienceSubs = [
            ['name' => 'Biology',       'description' => 'Study of living organisms',  'is_locked' => false],
            ['name' => 'Chemistry',     'description' => 'Study of matter',            'is_locked' => true],
            ['name' => 'Physics',       'description' => 'Study of forces and energy', 'is_locked' => true],
            ['name' => 'Earth Science', 'description' => 'Study of the Earth',         'is_locked' => true],
            ['name' => 'Astronomy',     'description' => 'Study of stars and space',   'is_locked' => true],
        ];

        foreach ($scienceSubs as $sub) {
            SubSubject::create([
                'subject_id'     => 2,
                'name'           => $sub['name'],
                'description'    => $sub['description'],
                'level_required' => 1,
                'is_locked'      => $sub['is_locked'],
            ]);
        }

        // English (subject_id: 3)
        $englishSubs = [
            ['name' => 'Grammar',               'description' => 'Rules of language',       'is_locked' => false],
            ['name' => 'Vocabulary',            'description' => 'Words and meanings',      'is_locked' => true],
            ['name' => 'Reading Comprehension', 'description' => 'Understanding texts',     'is_locked' => true],
            ['name' => 'Composition',           'description' => 'Writing structured text', 'is_locked' => true],
            ['name' => 'Writing Essay',         'description' => 'Express ideas',           'is_locked' => true],
        ];

        foreach ($englishSubs as $sub) {
            SubSubject::create([
                'subject_id'     => 3,
                'name'           => $sub['name'],
                'description'    => $sub['description'],
                'level_required' => 1,
                'is_locked'      => $sub['is_locked'],
            ]);
        }
    }
}