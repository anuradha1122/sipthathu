<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RaceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('races')->truncate();
        $data = [
            ['id' => 1, 'name' => 'Sinhala'],
            ['id' => 2, 'name' => 'Srilanka Tamil'],
            ['id' => 3, 'name' => 'Indian Tamil'],
            ['id' => 4, 'name' => 'Muslim'],
            ['id' => 5, 'name' => 'Other'],
        ];

        DB::table('races')->insert($data);
    }
}
