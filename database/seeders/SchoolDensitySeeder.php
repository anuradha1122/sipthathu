<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SchoolDensitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('school_densities')->truncate();

        $data = [
            ['id' => 1, 'name' => '1AB'],
            ['id' => 2, 'name' => '1C'],
            ['id' => 3, 'name' => 'Type 2'],
            ['id' => 4, 'name' => 'Type 3'],
        ];

        DB::table('school_densities')->insert($data);
    }
}
