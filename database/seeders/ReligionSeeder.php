<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReligionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('religions')->truncate();
        $data = [
            ['id' => 1, 'name' => 'Buddhism'],
            ['id' => 2, 'name' => 'Hindu'],
            ['id' => 3, 'name' => 'Islam'],
            ['id' => 4, 'name' => 'Catholic'],
            ['id' => 5, 'name' => 'Christian'],
            ['id' => 6, 'name' => 'Other'],
        ];

        DB::table('religions')->insert($data);
    }
}
