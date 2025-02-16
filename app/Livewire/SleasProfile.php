<?php

namespace App\Livewire;

use Livewire\Component;

class SleasProfile extends Component
{
    public $sleas;
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
    public function render()
    {
        return view('livewire.sleas-profile');
    }
}
