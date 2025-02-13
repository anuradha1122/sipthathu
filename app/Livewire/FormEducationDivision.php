<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Province;
use App\Models\District;
use App\Models\WorkPlace;

class FormEducationDivision extends Component
{
    public $division;

    public $provinces = [];
    public $districts = [];
    public $divisions = [];

    public $selectedProvince = null;
    public $selectedDistrict = null;

    public function mount()
    {
        // Load provinces initially
        $this->provinces = Province::where('active', 1)->get();
        $this->districts = collect();
        $this->divisions = collect();
    }

    public function updatedSelectedProvince($provinceId)
    {
        //dd($provinceId);
        // Load zones when a province is selected
        $this->districts = District::where('provinceId', $provinceId)->get();
        $this->divisions = collect(); // Reset divisions
        $this->selectedDistrict = null; // Reset district
    }

    public function updatedSelectedDistrict($districtId)
    {
        // Load divisions when a zone is selected
        $this->divisions = WorkPlace::join('offices', 'offices.workPlaceId', '=', 'work_places.id')
        ->where('offices.districtId', $districtId)
        ->where('offices.officeTypeId', 3)
        ->select('work_places.name', 'offices.id')
        ->get();
    }

    public function render()
    {
        //dd($this->size);
        return view('livewire.form-education-division');
    }
}
