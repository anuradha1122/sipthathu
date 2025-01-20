<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Student;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Crypt;

class StudentSearch extends Component
{
    public $search = '';
    public $size;

    public function mount($size)
    {
        $this->size = $size;
    }

    public function render()
    {
        // $searchResults = '';
        // if (strlen($this->search) > 0) {
        //     $searchResults = Student::join('student_personal_infos', 'students.id', '=', 'student_personal_infos.studentId')
        //         ->join('student_schools', 'students.id', '=', 'student_schools.studentId')
        //         ->join('schools', 'student_schools.schoolId', '=', 'schools.id')
        //         ->join('work_places', 'schools.workPlaceId', '=', 'work_places.id')
        //         ->where(function ($query) {
        //             $query->where('students.name', 'LIKE', '%' . $this->search . '%')
        //                   ->orWhere('students.studentNo', 'LIKE', '%' . $this->search . '%')
        //                   ->orWhere('students.nameWithInitials', 'LIKE', '%' . $this->search . '%');
        //         })
        //         ->where('students.active', 1)
        //         ->select(
        //             'students.id',
        //             'students.name',
        //             'students.nameWithInitials',
        //             'students.studentNo',
        //             'work_places.name AS school',
        //             'student_personal_infos.profilePicture'
        //         )
        //         ->paginate(10);
        //     //dd($searchResults);
        // }

        $searchResults = '';
        if (strlen($this->search) > 0) {
            $searchQuery = Student::join('student_personal_infos', 'students.id', '=', 'student_personal_infos.studentId')
                ->join('student_schools', 'students.id', '=', 'student_schools.studentId')
                ->join('schools', 'student_schools.schoolId', '=', 'schools.id')
                ->join('work_places', 'schools.workPlaceId', '=', 'work_places.id')
                ->where(function ($query) {
                    $query->where('students.name', 'LIKE', '%' . $this->search . '%')
                        ->orWhere('students.studentNo', 'LIKE', '%' . $this->search . '%')
                        ->orWhere('students.nameWithInitials', 'LIKE', '%' . $this->search . '%');
                })
                ->where('students.active', 1);

            // Apply filters based on user level
            if (session('schoolId')) {
                $searchQuery->where('schools.id', session('schoolId'));
            } elseif (session('officeId') && session('officeTypeId') == 3) {
                $searchQuery->where('schools.officeId', session('officeId'));
            } elseif (session('officeId') && session('officeTypeId') == 2) {
                $searchQuery->join('offices', 'schools.officeId', '=', 'offices.id')
                    ->where('offices.higherOfficeId', session('officeId'));
            } elseif (session('officeId') && session('officeTypeId') == 1) {
                $searchQuery->join('offices AS divisions', 'schools.officeId', '=', 'divisions.id')
                    ->join('offices AS zones', 'divisions.higherOfficeId', '=', 'zones.id')
                    ->where('zones.higherOfficeId', session('officeId'));
            } else {
                // No additional filter, fetch all results
            }

            // Finalize query
            $searchResults = $searchQuery->select(
                'students.id',
                'students.name',
                'students.nameWithInitials',
                'students.studentNo',
                'work_places.name AS school',
                'student_personal_infos.profilePicture'
            )->paginate(10);

            foreach ($searchResults as $result) {
                $result->stId = Crypt::encryptString($result->id);
            }

            //dd($searchResults);
        }

        
        return view('livewire.student-search', ['searchResults' => $searchResults]);
    }
}
