<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SchoolClassSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('school_classes')->truncate();

        $data = [
            ['id' => 1, 'name' => 'Grade 1-5'],
            ['id' => 2, 'name' => 'Grade 1-8'],
            ['id' => 3, 'name' => 'Grade 1-11'],
            ['id' => 4, 'name' => 'Grade 1-13'],
            ['id' => 5, 'name' => 'Grade 6-11'],
            ['id' => 6, 'name' => 'Grade 6-13'],
            ['id' => 7, 'name' => 'Other'],
        ];

        DB::table('school_classes')->insert($data);
    }
}
