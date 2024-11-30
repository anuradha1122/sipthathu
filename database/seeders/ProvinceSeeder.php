<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProvinceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('provinces')->truncate();

        $data = array(
            array('id' => '1','name' => 'Northern'),
            array('id' => '2','name' => 'Sabaragamuwa'),
            array('id' => '3','name' => 'North Western'),
            array('id' => '4','name' => 'North Central'),
            array('id' => '5','name' => 'Central'),
            array('id' => '6','name' => 'Southern'),
            array('id' => '7','name' => 'Western'),
            array('id' => '8','name' => 'Eastern'),
            array('id' => '9','name' => 'Uva')
          );

        DB::table('provinces')->insert($data);
    }
}
