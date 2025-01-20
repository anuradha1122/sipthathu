<?php

namespace App\Livewire;

use Livewire\Component;

class FormStudentNic extends Component
{
    public $nic;

    public function rules()
    {
        return [
            'nic' => [
                'required',
                'unique:students,nic',
                'regex:/^([0-9]{9}[Vv]|[0-9]{12})$/', // Matches either 9 digits followed by V/v or 12 digits
            ],
        ];
    }

    public function updatedNic()
    {
        $this->validate();
    }

    public function render()
    {
        return view('livewire.form-student-nic',[
            'nic' => $this->nic,
        ]);
    }
}
