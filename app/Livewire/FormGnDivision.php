<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Province;
use App\Models\District;
use App\Models\DsDivision;
use App\Models\GnDivision;

class FormGnDivision extends Component
{
    public $gnDivision;

    public $provinces = [];
    public $districts = [];
    public $dsDivisions = [];
    public $gnDivisions = [];

    public $selectedProvince = null;
    public $selectedDistrict = null;
    public $selectedDsDivision = null;
    public $selectedGnDivision = null;

    public function mount()
    {
        $this->provinces = Province::where('active', 1)->get();
    }

    public function updatedSelectedProvince($provinceId)
    {
        $this->districts = District::where('provinceId', $provinceId)
                                   ->where('active', 1)
                                   ->get();
        $this->dsDivisions = [];
        $this->gnDivisions = [];
        $this->selectedDistrict = null;
        $this->selectedDsDivision = null;
        $this->selectedGnDivision = null;
    }

    public function updatedSelectedDistrict($districtId)
    {
        $this->dsDivisions = DsDivision::where('districtId', $districtId)
                                       ->where('active', 1)
                                       ->get();
        $this->gnDivisions = [];
        $this->selectedDsDivision = null;
        $this->selectedGnDivision = null;
    }

    public function updatedSelectedDsDivision($dsId)
    {
        $this->gnDivisions = GnDivision::where('dsId', $dsId)
                                        ->where('active', 1)
                                        ->selectRaw("CONCAT(name, ' - ', gnCode) AS name, id")
                                        ->orderBy('name')
                                        ->get();
    }

    public function render()
    {
        return view('livewire.form-gn-division');
    }
}
