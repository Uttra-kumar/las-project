<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class StreamsTableSeeder extends Seeder
{
    public function run(): void
    {
        // First, truncate tables in correct order
        Schema::disableForeignKeyConstraints();
        DB::table('student_streams')->truncate();
        DB::table('stream_subjects')->truncate();
        DB::table('streams')->truncate();
        Schema::enableForeignKeyConstraints();
        
        // Streams data
        $streams = [
            [
                'stream_id' => 'STR001',
                'stream_name' => 'Science-Mathematics',
                'description' => 'For students who want to pursue engineering or pure sciences',
                'applicable_classes' => json_encode(['11', '12']),
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'stream_id' => 'STR002',
                'stream_name' => 'Science-Biology',
                'description' => 'For students who want to pursue medical or life sciences',
                'applicable_classes' => json_encode(['11', '12']),
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'stream_id' => 'STR003',
                'stream_name' => 'Commerce',
                'description' => 'For students who want to pursue business, finance or accounting',
                'applicable_classes' => json_encode(['11', '12']),
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'stream_id' => 'STR004',
                'stream_name' => 'Arts/Humanities',
                'description' => 'For students interested in humanities, social sciences or law',
                'applicable_classes' => json_encode(['11', '12']),
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
        
        DB::table('streams')->insert($streams);
        
        // Get subject IDs (assuming subjects table already has data)
        // You'll need to adjust these IDs based on your actual subjects
        // For now, we'll use placeholder IDs
        $physicsId = 1;
        $chemistryId = 2;
        $mathsId = 3;
        $biologyId = 9;
        $englishId = 4;
        $csId = 5;
        $ipId = 6;
        $economicsId = 7;
        $peId = 8;
        $psychologyId = 10;
        $accountsId = 11;
        $businessStudiesId = 12;
        $historyId = 13;
        $geographyId = 14;
        $polScienceId = 15;
        
        // Stream Subjects data
        $streamSubjects = [
            // Science-Maths
            [
                'stream_id' => 1,
                'core_subjects' => json_encode([$physicsId, $chemistryId, $mathsId, $englishId]),
                'optional_pool' => json_encode([$csId, $ipId, $economicsId, $peId]),
                'max_optionals' => 2,
                'session_id' => '2425',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Science-Biology
            [
                'stream_id' => 2,
                'core_subjects' => json_encode([$physicsId, $chemistryId, $biologyId, $englishId]),
                'optional_pool' => json_encode([$csId, $ipId, $psychologyId, $peId]),
                'max_optionals' => 2,
                'session_id' => '2425',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Commerce
            [
                'stream_id' => 3,
                'core_subjects' => json_encode([$accountsId, $businessStudiesId, $economicsId, $englishId]),
                'optional_pool' => json_encode([$mathsId, $ipId, $psychologyId, $peId]),
                'max_optionals' => 2,
                'session_id' => '2425',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Arts
            [
                'stream_id' => 4,
                'core_subjects' => json_encode([$historyId, $geographyId, $polScienceId, $englishId]),
                'optional_pool' => json_encode([$psychologyId, $economicsId, $peId]),
                'max_optionals' => 2,
                'session_id' => '2425',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
        
        DB::table('stream_subjects')->insert($streamSubjects);
    }
}