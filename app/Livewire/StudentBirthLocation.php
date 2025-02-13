<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Province;
use App\Models\District;
use App\Models\DsDivision;
use App\Models\GnDivision;

class StudentBirthLocation extends Component
{
    public $gnDivision;


    public $birthProvinceErr;

    public $provinces = [];
    public $districts;
    public $dsDivisions;
    public $gnDivisions = [];

    public $selectedProvince = null;
    public $selectedDistrict = null;
    public $selectedDsDivision = null;
    public $selectedGnDivision = null;

    public $errorsFromController = [];

    public function mount($errorsFromController = null)
    {
        if ($errorsFromController) {
            $this->errorsFromController = $errorsFromController->getBag('default')->messages();
        }
        $this->provinces = Province::where('active', 1)->get();
        $this->districts = collect();
        $this->dsDivisions = collect();
        $this->gnDivisions = collect();
    }

    public function updatedSelectedProvince($provinceId)
    {
        $this->districts = District::where('provinceId', $provinceId)
                                   ->where('active', 1)
                                   ->get();
        $this->dsDivisions = collect();
        $this->gnDivisions = collect();
        $this->selectedDistrict = null;
        $this->selectedDsDivision = null;
        $this->selectedGnDivision = null;
    }

    public function updatedSelectedDistrict($districtId)
    {
        $this->dsDivisions = DsDivision::where('districtId', $districtId)
                                       ->where('active', 1)
                                       ->get();
        $this->gnDivisions = collect();
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
        return view('livewire.student-birth-location');
    }
}
