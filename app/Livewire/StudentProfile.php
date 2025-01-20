<?php

namespace App\Livewire;

use Livewire\Component;

class StudentProfile extends Component
{
    public $student;
    public $heading;

    public $nameIsEdit;
    public function render()
    {
        return view('livewire.student-profile');
    }
}
