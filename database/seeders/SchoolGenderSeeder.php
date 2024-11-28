<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SchoolGenderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('school_genders')->truncate();
        $data = [
            ['id' => 1, 'name' => 'Boys School'],
            ['id' => 2, 'name' => 'Girls School'],
            ['id' => 3, 'name' => 'Mixed School'],
            ['id' => 4, 'name' => 'Girls School (Except A/L or Primary)'],
            ['id' => 5, 'name' => 'Boys School (Except A/L or Primary)'],
        ];

        DB::table('school_genders')->insert($data);
    }
}
