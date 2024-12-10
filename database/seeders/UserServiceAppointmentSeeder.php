<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserServiceAppointmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('user_service_appointments')->truncate();
        $data = array(
            array('id' => '1','userServiceId' => '1','workPlaceId' => '10042','appointedDate' => '2024-11-26','releasedDate' => NULL,'appointmentType' => '1'),
            array('id' => '2','userServiceId' => '2','workPlaceId' => '10043','appointedDate' => '2024-11-26','releasedDate' => NULL,'appointmentType' => '1')
        );
        DB::table('user_service_appointments')->insert($data);
    }
}
