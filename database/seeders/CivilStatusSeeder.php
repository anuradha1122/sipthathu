<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CivilStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('civil_statuses')->truncate();

        $data = [
            ['id' => 1, 'name' => 'Married'],
            ['id' => 2, 'name' => 'Single'],
            ['id' => 3, 'name' => 'Divorced'],
            ['id' => 4, 'name' => 'Rev'],
            ['id' => 5, 'name' => 'Other'],
        ];

        DB::table('civil_statuses')->insert($data);
    }
}
