<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SchoolAuthoritySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('school_authorities')->truncate();

        $data = [
            ['id' => 1, 'name' => 'National School'],
            ['id' => 2, 'name' => 'Provincial School'],
        ];

        DB::table('school_authorities')->insert($data);
    }
}
