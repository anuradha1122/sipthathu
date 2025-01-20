<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BloodGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('blood_groups')->truncate();
        $data = [
            ['id' => 1, 'name' => 'A+'],
            ['id' => 2, 'name' => 'A-'],
            ['id' => 3, 'name' => 'B+'],
            ['id' => 4, 'name' => 'B-'],
            ['id' => 5, 'name' => 'AB+'],
            ['id' => 6, 'name' => 'AB-'],
            ['id' => 7, 'name' => 'O+'],
            ['id' => 8, 'name' => 'O-'],
        ];

        DB::table('blood_groups')->insert($data);
    }
}
