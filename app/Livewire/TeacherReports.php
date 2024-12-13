<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Race;
use App\Models\Religion;
use App\Models\CivilStatus;


class TeacherReports extends Component
{
    public $race;
    public $religion;
    public $civilStatus;
    public $birthDayStart;
    public $birthDayEnd;

    public $raceList;
    public $religionList;
    public $civilStatusList;

    //public $results =[];

    public function generateReports(){
        //dd($this->library);
        return $results = DB::table('users')
        ->join('personal_infos', 'users.id', '=', 'personal_infos.userId')
        ->leftJoin('civil_statuses', 'personal_infos.civilStatusId', '=', 'civil_statuses.id')
        ->leftJoin('religions', 'personal_infos.religionId', '=', 'religions.id')
        ->leftJoin('races', 'personal_infos.raceId', '=', 'races.id')
        ->when($this->race != null && $this->race != 0, function ($query) {
            return $query->where('personal_infos.raceId', $this->race);
        })
        ->when($this->religion != null && $this->religion != 0, function ($query) {
            return $query->where('personal_infos.religionId', $this->religion);
        })
        ->when($this->civilStatus != null && $this->civilStatus != 0, function ($query) {
            return $query->where('personal_infos.civilStatusId', $this->civilStatus);
        })
        ->when(true, function ($query) {
            $startDate = $this->birthDayStart ?? '1900-01-01';
            $endDate = $this->birthDayEnd ?? Carbon::now()->toDateString();
            return $query->whereBetween('personal_infos.birthDay', [$startDate, $endDate]);
        })
        ->select(
            'users.name AS userName',
            'users.nameWithInitials',
            'users.nic',
            'users.email',
            'races.name AS race',
            'religions.name AS religion',
            'civil_statuses.name AS civilStatus',
            'personal_infos.birthDay'
        )
        ->orderBy('personal_infos.birthDay', 'DESC')
        ->orderBy('users.name', 'DESC')
        ->paginate(10);
    
        
    }

    public function render()
    {
        $this->raceList = Race::where('active', 1)->get();
        $this->religionList = Religion::where('active', 1)->get();
        $this->civilStatusList = CivilStatus::where('active', 1)->get();

        $results = $this->generateReports();
        return view('livewire.teacher-reports', ['results' => $results]);
    }
}
