<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CenterTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('center_types')->truncate();

        $data = [
            ['id' => 1, 'name' => 'ITDLH'],
            ['id' => 2, 'name' => 'Teacher Training Center'],
        ];

        DB::table('center_types')->insert($data);
    }
}
