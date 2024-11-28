<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FamilyMemberTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('family_member_types')->truncate();

        $data = [
            ['id' => 1, 'name' => 'Wife'],
            ['id' => 2, 'name' => 'husband'],
            ['id' => 3, 'name' => 'Daughter'],
            ['id' => 4, 'name' => 'Son'],
            ['id' => 5, 'name' => 'Father'],
            ['id' => 6, 'name' => 'Mother'],
        ];

        DB::table('family_member_types')->insert($data);
    }
}
