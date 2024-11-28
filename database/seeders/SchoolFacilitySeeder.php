<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SchoolFacilitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('school_facilities')->truncate();
        $data = [
            ['id' => 1, 'name' => 'More Convenient'],
            ['id' => 2, 'name' => 'Convenient'],
            ['id' => 3, 'name' => 'Not Convenient'],
            ['id' => 4, 'name' => 'Difficult'],
            ['id' => 5, 'name' => 'Very Difficult'],
        ];
        DB::table('school_facilities')->insert($data);
    }
}
