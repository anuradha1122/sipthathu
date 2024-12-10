<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserServiceAppointmentPositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('user_service_appointment_positions')->truncate();
        $data = array(
            array('id' => '1','userServiceAppointmentId' => '1','positionId' => '1','positionedDate' => '2024-11-26','releasedDate' => NULL),
            array('id' => '2','userServiceAppointmentId' => '2','positionId' => '1','positionedDate' => '2024-11-26','releasedDate' => NULL)
          );
        DB::table('user_service_appointment_positions')->insert($data);
    }
}
