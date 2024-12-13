<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\WorkPlace;
use App\Models\School;
use Illuminate\Support\Facades\Auth;

class FormUserSchool extends Component
{
    public $zones;
    public $selectedZone;
    public $schools = [];
    public $selectedSchool;

    public function updatedSelectedZone($zoneId)
    {
        if (session()->has('ministryId') && session('ministryId') === 1){
            $this->schools = WorkPlace::select('work_places.id', 'work_places.name as name')
            ->join('schools', 'work_places.id', '=', 'schools.workPlaceId')
            ->join('offices AS divisions', 'divisions.id', '=', 'schools.officeId')
            ->where('work_places.active', 1)
            ->where('divisions.active', 1)
            ->where('schools.active', 1)
            ->where('divisions.higherOfficeId', $zoneId)
            ->where('schools.authorityId', 1)
            ->orderBy('work_places.name', 'ASC')
            ->get();
        }else{
            $this->schools = WorkPlace::select('work_places.id', 'work_places.name as name')
            ->join('schools', 'work_places.id', '=', 'schools.workPlaceId')
            ->join('offices AS divisions', 'divisions.id', '=', 'schools.officeId')
            ->where('work_places.active', 1)
            ->where('divisions.active', 1)
            ->where('schools.active', 1)
            ->where('divisions.higherOfficeId', $zoneId)
            ->orderBy('work_places.name', 'ASC')
            ->get();
        }
        
    }

    public function rules()
    {
        return [
            'zone' => 'required',
            'school' => 'required',
        ];
    }

    public function render()
    {
        //dd(session('ministryId'));
        if (session('officeTypeId') == 2) {
            $this->zones = null;
            $this->schools = WorkPlace::select('work_places.id', 'work_places.name as name')
            ->join('schools', 'work_places.id', '=', 'schools.workPlaceId')
            ->join('offices AS divisions', 'divisions.id', '=', 'schools.officeId')
            ->where('work_places.active', 1)
            ->where('divisions.active', 1)
            ->where('schools.active', 1)
            ->where('divisions.higherOfficeId', session('officeId'))
            ->orderBy('work_places.name', 'ASC')
            ->get();
        }
        elseif (session('officeTypeId') == 1) {
            $this->zones = WorkPlace::select('offices.id', 'work_places.name as name')
            ->join('offices', 'work_places.id', '=', 'offices.workPlaceId')
            ->where('work_places.active', 1)
            ->where('offices.active', 1)
            ->where('offices.officeTypeId', 2)
            ->where('offices.higherOfficeId', session('officeId'))
            ->orderBy('work_places.name', 'ASC') // Order by work_places.name in ascending order
            ->get();
            //dd($this->zones);
        }
        elseif (session()->has('ministryId') && session('ministryId') === 1){
            $this->zones = WorkPlace::select('offices.id', 'work_places.name as name')
            ->join('offices', 'work_places.id', '=', 'offices.workPlaceId')
            ->where('work_places.active', 1)
            ->where('offices.active', 1)
            ->where('offices.officeTypeId', 2)
            ->orderBy('work_places.name', 'ASC') // Order by work_places.name in ascending order
            ->get();
        }

        //dd($this->zones);
        return view('livewire.form-user-school',['zones' => $this->zones], ['schools' => $this->schools]);
    }
}
