<?php

namespace App\Livewire;

use Livewire\Component;

class NonacademicProfile extends Component
{

    public $nonacademic;
    public $service;
    public $appointment;
    public $position;
    public $previousServices;
    public $currentService;
    public $previousServiceRanks;
    public $currentServiceRanks;
    public $previousAppointments;
    public $currentAppointments;
    public $previousAttachAppointments;
    public $currentAttachAppointment;
    public $educationQualifications;
    public $professionalQualifications;
    public $family;
    public $loginAction = 0;
    public $profileAction = 0;

    public function mount()
    {
        //dd(session('positionId'));
        if (in_array(session('positionId'), [4, 6, 7, 10])) {
            $this->loginAction = 1;
        }
        if (in_array(session('positionId'), [6])) {
            $this->profileAction = 1;
        }
        //dd($this->loginAction);

    }


    public function render()
    {
        return view('livewire.nonacademic-profile');
    }
}
