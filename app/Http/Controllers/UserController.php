<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;

use Illuminate\Http\Request;
use App\Models\Subject;
use App\Models\AppointmentMedium;
use App\Models\User;
use App\Models\UserInService;
use App\Models\UserServiceAppointment;
use App\Models\UserServiceAppointmentPosition;
use App\Models\ContactInfo;
use App\Models\PersonalInfo;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function teacherindex()
    {
        $chartData = [
            ['Book Catagory', 'Amount'],
            ["Novels", 44],
            ["Short Story", 31],
            ["Documantary", 12],
            ["Children's Boos", 10],
            ['Other', 3]
        ];
        $option = ['Dashboard' => 'teacher.dashboard'];
        
        $card_pack_1 = collect([
            (object) [
                'id' => 1,
                'name' => 'Teacher',
                'user_count' => 25,
            ],
            (object) [
                'id' => 2,
                'name' => 'Principal',
                'user_count' => 5,
            ],
            (object) [
                'id' => 3,
                'name' => 'SLEAS',
                'user_count' => 10,
            ],
            (object) [
                'id' => 4,
                'name' => 'SLAS',
                'user_count' => 8,
            ],
            (object) [
                'id' => 5,
                'name' => 'Development Officer',
                'user_count' => 12,
            ],
        ]);
        //dd($card_pack_1);
        return view('teacher/dashboard',compact('option','card_pack_1','chartData'));
    }

    public function principalindex()
    {
        $chartData = [
            ['Book Catagory', 'Amount'],
            ["Novels", 44],
            ["Short Story", 31],
            ["Documantary", 12],
            ["Children's Boos", 10],
            ['Other', 3]
        ];
        $option = ['Dashboard' => 'principal.dashboard'];
        
        $card_pack_1 = collect([
            (object) [
                'id' => 1,
                'name' => 'Teacher',
                'user_count' => 25,
            ],
            (object) [
                'id' => 2,
                'name' => 'Principal',
                'user_count' => 5,
            ],
            (object) [
                'id' => 3,
                'name' => 'SLEAS',
                'user_count' => 10,
            ],
            (object) [
                'id' => 4,
                'name' => 'SLAS',
                'user_count' => 8,
            ],
            (object) [
                'id' => 5,
                'name' => 'Development Officer',
                'user_count' => 12,
            ],
        ]);
        //dd($card_pack_1);
        return view('principal/dashboard',compact('option','card_pack_1','chartData'));
    }

    public function teachercreate()
    {
        $subjects = Subject::where('active', 1)->get();
        $appointedMediums = AppointmentMedium::where('active', 1)->get();

        $option = [
            'Dashboard' => 'teacher.dashboard',
            'Teacher Registration' => 'teacher.register'
        ];
        return view('teacher/register',compact('option','subjects','appointedMediums'));
    }

    public function teacherstore(StoreUserRequest $request)
    {
        $subjects = Subject::where('active', 1)->get();
        $appointedMediums = AppointmentMedium::where('active', 1)->get();

        $option = [
            'Dashboard' => 'teacher.dashboard',
            'Teacher Registration' => 'teacher.register'
        ];
        //dd($request);
        //$validatedData = $request->validated();
        $name = $request->name;
        // Convert the full name to an array of words
        $nameParts = explode(' ', $name);
        // Convert all parts to title case
        $nameParts = array_map('ucfirst', $nameParts);
        // Get the last name (the last element in the array)
        $lastName = array_pop($nameParts);
        // Generate initials for the rest of the names
        $initials = array_map(function($part) {
            return strtoupper($part[0]) . '.';
        }, $nameParts);
        // Combine initials and last name
        $nameWithInitials = implode('', $initials) . ' ' . $lastName;
    

        $user = User::create([
            'name' => ucwords(strtolower($request->name)),
            'nameWithInitials' => $nameWithInitials,
            'nic' => strtoupper($request->nic),
            'password' => Hash::make(substr($request->nic, 0, 6)),
        ]);

        $userInService = UserInService::create([
            'userId' => $user->id,
            'serviceId' => 1,
            'appointedDate' => $request->serviceDate,
        ]);

        $userServiceAppointment = UserServiceAppointment::create([
            'userServiceId' => $userInService->id,
            'workPlaceId' => $request->school,
            'appointedDate' => $request->serviceDate,
            'appointmentType' => 1,
        ]);

        $contactInfo = ContactInfo::create([
            'userId' => $user->id,
            'permAddressLine1' => ucwords(strtolower($request->addressLine1)),
            'permAddressLine2' => ucwords(strtolower($request->addressLine2)),
            'permAddressLine3' => ucwords(strtolower($request->addressLine3)),
            'mobile1' => $request->mobile,
        ]);
        $contactInfo->save();

        $personalInfo = PersonalInfo::create([
            'userId' => $user->id,
            'genderId' => $request->gender,
            'birthDay' => $request->birthDay,
        ]);
        $personalInfo->save();

        $position = UserServiceAppointmentPosition::create([
            'userServiceAppointmentId' => $userServiceAppointment->id,
            'positionId' => 1,
            'positionedDate' => $request->serviceDate,
        ]);

        session()->flash('success', 'User has been successfully registered!');
        
        return view('teacher/register',compact('option','subjects','appointedMediums'));
    }

    public function teachersearch()
    {
        $option = [
            'Teacher Dashboard' => 'teacher.dashboard',
            'Teacher Search' => 'teacher.search'
        ];
        return view('teacher/search',compact('option'));
    }

    public function teacherprofile(Request $request)
    {
        $option = [
            'Teacher Dashboard' => 'teacher.dashboard',
            'Teacher Search' => 'teacher.search',
            'Teacher Profile' => 'teacher.profile'
        ];
        if($request->has('id')){
            $teacher = User::join('personal_infos', 'users.id', '=', 'personal_infos.userId')
            ->join('contact_infos', 'users.id', '=', 'contact_infos.userId')
            ->leftjoin('races', 'personal_infos.raceId', '=', 'races.id')
            ->leftjoin('religions', 'personal_infos.religionId', '=', 'religions.id')
            ->leftjoin('civil_statuses', 'personal_infos.civilStatusId', '=', 'civil_statuses.id')
            ->where('users.id', $request->id)
            ->select(
                'users.id AS userId','users.name AS userName','users.nic','users.email','users.nameWithInitials',
                'personal_infos.birthDay',
                DB::raw("CASE 
                    WHEN personal_infos.genderId = 1 THEN 'Male' 
                    WHEN personal_infos.genderId = 2 THEN 'Female' 
                    ELSE 'Unknown' 
                END AS gender"),
                'races.name AS race',
                'religions.name AS religion',
                'civil_statuses.name AS civilStatus',
                'contact_infos.permAddressLine1',
                'contact_infos.permAddressLine2',
                'contact_infos.permAddressLine3',
                'contact_infos.tempAddressLine1',
                'contact_infos.tempAddressLine2',
                'contact_infos.tempAddressLine3',
                'contact_infos.mobile1',
                'contact_infos.mobile2',
            )
            ->first();

            $services = UserInService::join('services', 'user_in_services.serviceId', '=', 'services.id')
            ->leftjoin('user_service_in_ranks', 'user_in_services.id', '=', 'user_service_in_ranks.userServiceId')
            ->leftjoin('ranks', 'user_service_in_ranks.rankId', '=', 'ranks.id')
            ->where('user_in_services.userId', $request->id)
            ->select(
                'user_in_services.id AS userServiceId',
                'user_in_services.appointedDate',
                'user_in_services.releasedDate',
                'user_in_services.current AS currentService',
                'services.name AS serviceName',
                'user_service_in_ranks.id AS serviceRankId',
                'user_service_in_ranks.rankId',
                'user_service_in_ranks.rankedDate',
                'user_service_in_ranks.current AS currentRank',
                'ranks.name AS rank',
            )
            ->get();

            $service = [];
            foreach ($services as $key => $value) {
                $service[$value->serviceRankId] = $value->serviceName."(".$value->appointedDate." - ".$value->releasedDate.") [".$value->rank."(Ranked Date - ".$value->rankedDate.")]";
            }

            $appointments = UserInService::join('user_service_appointments', 'user_in_services.serviceId', '=', 'user_service_appointments.userServiceId')
            ->join('services', 'user_in_services.serviceId', '=', 'services.id')
            ->join('work_places', 'user_service_appointments.workPlaceId', '=', 'work_places.id')
            ->where('user_in_services.userId', $request->id)
            ->select(
                'user_in_services.serviceId',
                'services.name AS serviceName',
                'user_service_appointments.id AS userAppointmentId',
                'user_service_appointments.appointedDate',
                'user_service_appointments.releasedDate',
                'user_service_appointments.current AS currentService',
                'work_places.name AS workPlaceName',
                'work_places.censusNo AS censusNo',
                'work_places.categoryId AS workPlaceCategory',
            )
            ->get();

            $appointment = [];
            $appointments_key = [];
            foreach ($appointments as $key => $value) {
                $appointments_key[] = $value->userAppointmentId;
                $appointment[$value->userAppointmentId] =$value->workPlaceName."(".$value->appointedDate." - ".$value->releasedDate.") [".$value->serviceName.")]";
            }

            //dd($appointments_key);

            $positions = UserServiceAppointment::join('user_service_appointment_positions', 'user_service_appointments.id', '=', 'user_service_appointment_positions.userServiceAppointmentId')
            ->join('positions', 'user_service_appointment_positions.positionId', '=', 'positions.id')
            ->join('work_places', 'user_service_appointments.workPlaceId', '=', 'work_places.id')
            ->whereIn('user_service_appointments.id', $appointments_key)
            ->select(
                'user_service_appointment_positions.id AS appointmentPositionId',
                'user_service_appointment_positions.positionedDate',
                'user_service_appointment_positions.releasedDate',
                'user_service_appointments.id AS userAppointmentId',
                'user_service_appointments.current AS currentAppointment',
                'positions.name AS positionName',
                'work_places.name AS workPlaceName',
                'user_service_appointment_positions.current AS currentPostion',
            )
            ->get();

            $position = [];
            foreach ($positions as $key => $value) {
                $position[$value->appointmentPositionId] = $value->positionName."(".$value->positionedDate." - ".$value->releasedDate.") [".$value->workPlaceName.")]";
            }

            //dd($position);
            //$teacher = null;
            return view('teacher/profile',compact('teacher','service','appointment','position','option'));
            
        }else{
            return redirect()->route('teacher.search');
        }
    }

    public function teacherreports()
    {
        $option = [
            'Teacher Dashboard' => 'teacher.dashboard',
            'Teacher Reports' => 'teacher.reports'
        ];
        return view('teacher/reports',compact('option'));
    }
}
