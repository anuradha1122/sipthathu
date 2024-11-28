<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MinistryTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('ministry_types')->truncate();

        $data = [
            ['id' => 1, 'name' => 'Provincial Ministry', 'level' => 2],
            ['id' => 2, 'name' => 'Main Ministry', 'level' => 1],
        ];

        DB::table('ministry_types')->insert($data);
    }
}
