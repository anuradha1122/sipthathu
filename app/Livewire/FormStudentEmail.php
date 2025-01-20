<?php

namespace App\Livewire;

use Livewire\Component;

class FormStudentEmail extends Component
{
    public $email;

    public function rules()
    {
        return [
            'email' => [
                'nullable',
                'unique:students,email',
                'email', 
            ],
        ];
    }

    public function updatedEmail()
    {
        $this->validate();
    }

    public function render()
    {
        return view('livewire.form-student-email',[
            'email' => $this->email,
        ]);
    }
}
