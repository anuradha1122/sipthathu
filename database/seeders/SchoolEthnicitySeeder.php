<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SchoolEthnicitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('school_ethnicities')->truncate();

        $data = [
            ['id' => 1, 'name' => 'Sinhala'],
            ['id' => 2, 'name' => 'Tamil'],
            ['id' => 3, 'name' => 'Muslim'],
        ];

        DB::table('school_ethnicities')->insert($data);
    }
}
