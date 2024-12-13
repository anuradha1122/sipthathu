<?php

namespace App\Livewire;

use Livewire\Component;

class TeacherProfile extends Component
{ 

    public $name;
    public $nameWithInitials;
    public $email;
    public $nic;
    public $race;
    public $religion;
    public $civilStatus;
    public $birthDay;
    public $gender;
    public $permAddressLine1;
    public $permAddressLine2;
    public $permAddressLine3;
    public $tempAddressLine1;
    public $tempAddressLine2;
    public $tempAddressLine3;
    public $mobile1;
    public $mobile2;
    public $service;
    public $appointment;
    public $position;

    public $nameIsEdit = false;
    public $emailIsEdit;
    public $nicIsEdit;
    public $raceIsEdit;
    public $religionIsEdit;
    public $civilStatusIsEdit;
    public $birthDayIsEdit;
    public $genderIsEdit;
    public $permAddressIsEdit;
    public $tempAddressIsEdit;
    public $mobileIsEdit;
    public $serviceIsEdit;
    public $appointmentIsEdit;
    public $positionIsEdit;


    public function render()
    {
        return view('livewire.teacher-profile');
    }
}
