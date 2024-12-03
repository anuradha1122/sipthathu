<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubjectSectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('subject_sections')->truncate();

        $data = [
            ['id' => 1, 'name' => 'Primary'],
            ['id' => 2, 'name' => 'O/L'],
            ['id' => 3, 'name' => 'A/L'],
        ];

        DB::table('subject_sections')->insert($data);
    }
}
