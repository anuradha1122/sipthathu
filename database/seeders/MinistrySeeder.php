<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MinistrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('ministries')->truncate();

        $data = array(
            array('workPlaceId' => '1','officeId' => NULL,'ministryNo' => 'M1'),
            array('workPlaceId' => '2','officeId' => '1','ministryNo' => 'M2'),
            array('workPlaceId' => '3','officeId' => '2','ministryNo' => 'M3'),
            array('workPlaceId' => '4','officeId' => '3','ministryNo' => 'M4'),
            array('workPlaceId' => '5','officeId' => '4','ministryNo' => 'M5'),
            array('workPlaceId' => '6','officeId' => '5','ministryNo' => 'M6'),
            array('workPlaceId' => '7','officeId' => '6','ministryNo' => 'M7'),
            array('workPlaceId' => '8','officeId' => '7','ministryNo' => 'M8'),
            array('workPlaceId' => '9','officeId' => '8','ministryNo' => 'M9'),
            array('workPlaceId' => '10','officeId' => '9','ministryNo' => 'M10')
          );

        DB::table('ministries')->insert($data);
    }
}
