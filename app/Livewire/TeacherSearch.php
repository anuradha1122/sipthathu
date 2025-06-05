<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Crypt;

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
            ->leftjoin('personal_infos', 'users.id', '=', 'personal_infos.userId')
            ->when($this->search, function ($query) {
                $query->where(function ($subQuery) {
                    $subQuery->where('users.name', 'LIKE', '%' . $this->search . '%')
                        ->orWhere('users.nic', 'LIKE', '%' . $this->search . '%')
                        ->orWhere('users.nameWithInitials', 'LIKE', '%' . $this->search . '%');
                });
            })
            ->where('user_service_appointments.current', 1)
            ->where('user_in_services.serviceId', 1)
            ->select('users.*', 'personal_infos.profilePicture')
            ->paginate(10);

            foreach ($searchResults as $result) {
                $result->usId = Crypt::encryptString($result->id);
            }

        }
        return view('livewire.teacher-search', ['searchResults' => $searchResults]);
    }
}


