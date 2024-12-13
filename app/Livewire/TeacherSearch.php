<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;

class TeacherSearch extends Component
{
    public $search = '';
    public $size;

    public function mount($size)
    {
        $this->size = $size;
    }

    public function render()
    {
        $searchResults = '';
        if(strlen($this->search)>0){
            $searchResults = User::join('user_in_services', 'users.id', '=', 'user_in_services.userId')
            ->join('user_service_appointments', 'user_in_services.id', '=', 'user_service_appointments.userServiceId')
            ->when($this->search, function ($query) {
                $query->where(function ($subQuery) {
                    $subQuery->where('users.name', 'LIKE', '%' . $this->search . '%')
                        ->orWhere('users.nic', 'LIKE', '%' . $this->search . '%')
                        ->orWhere('users.nameWithInitials', 'LIKE', '%' . $this->search . '%');
                });
            })
            ->where('user_service_appointments.appointmentType', 1)
            ->select('users.*')
            ->paginate(10);

        }
        return view('livewire.teacher-search', ['searchResults' => $searchResults]);
    }
}


