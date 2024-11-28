<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WorkPlaceCatagorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('work_place_catagories')->truncate();

        $data = [
            ['id' => 1, 'name' => 'School'],
            ['id' => 2, 'name' => 'office'],
            ['id' => 3, 'name' => 'Ministry'],
            ['id' => 4, 'name' => 'Center'],
        ];

        DB::table('work_place_catagories')->insert($data);
    }
}
