<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Subject;
use App\Models\AppointmentMedium;
use App\Models\AppointmentCategory;
use App\Models\Race;
use App\Models\CivilStatus;
use App\Models\Religion;
use App\Models\BloodGroup;
use App\Models\Illness;
use App\Models\Rank;
use App\Models\User;
use App\Models\UserInService;
use App\Models\Service;
use App\Models\UserServiceInRank;
use App\Models\UserServiceAppointment;
use App\Models\UserServiceAppointmentPosition;
use App\Models\TeacherService;
use App\Models\ContactInfo;
use App\Models\PersonalInfo;
use App\Models\LocationInfo;
use App\Models\EducationQualification;
use App\Models\ProfessionalQualification;
use App\Models\FamilyInfo;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;


class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function myprofile()
    {
        //dd(session('serviceId'));
        if(session('serviceId')){
            if(Auth::user()->id){
                try{

                    $option = [
                        'Dashboard' => 'dashboard',
                        'My Profile' => route('profile.myprofile'),
                    ];

                    //$decryptedId = Crypt::decryptString($request->id);
                    //dd($decryptedId);
                    $user = User::leftjoin('personal_infos', 'users.id', '=', 'personal_infos.userId')
                    ->leftjoin('races', 'personal_infos.raceId', '=', 'races.id')
                    ->leftjoin('religions', 'personal_infos.religionId', '=', 'religions.id')
                    ->leftjoin('civil_statuses', 'personal_infos.civilStatusId', '=', 'civil_statuses.id')
                    ->leftjoin('contact_infos', 'users.id', '=', 'contact_infos.userId')
                    ->leftjoin('location_infos', 'users.id', '=', 'location_infos.userId')
                    //->leftjoin('offices', 'location_infos.educationDivisionId', '=', 'offices.id')
                    ->leftjoin('work_places AS educationDivisions', 'location_infos.educationDivisionId', '=', 'educationDivisions.id')
                    ->leftjoin('gn_divisions', 'location_infos.gnDivisionId', '=', 'gn_divisions.id')
                    ->leftjoin('ds_divisions', 'gn_divisions.dsId', '=', 'ds_divisions.id')
                    ->leftjoin('districts', 'ds_divisions.districtId', '=', 'districts.id')
                    ->leftjoin('provinces', 'districts.provinceId', '=', 'provinces.id')
                    ->where('users.id', Auth::user()->id)
                    ->select(
                        'users.id AS userId','users.name AS name','users.nic','users.email','users.nameWithInitials',
                        'personal_infos.birthDay','personal_infos.profilePicture',
                        DB::raw("CASE
                            WHEN personal_infos.genderId = 1 THEN 'Male'
                            WHEN personal_infos.genderId = 2 THEN 'Female'
                            ELSE 'Unknown'
                        END AS gender"),
                        'races.name AS race',
                        'religions.name AS religion',
                        'civil_statuses.name AS civilStatus',
                        'contact_infos.*',
                        'educationDivisions.name AS educationDivision',
                        'gn_divisions.name AS gnDivision',
                        'ds_divisions.name AS dsDivision',
                        'districts.name AS district',
                        'provinces.name AS province',
                    )
                    ->first();

                    $combinedData = UserInService::join('services', 'user_in_services.serviceId', '=', 'services.id')
                        ->leftJoin('user_service_in_ranks', 'user_in_services.id', '=', 'user_service_in_ranks.userServiceId')
                        ->leftJoin('ranks', 'user_service_in_ranks.rankId', '=', 'ranks.id')
                        ->where('user_in_services.userId', Auth::user()->id)
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
                            'ranks.name AS rank'
                        )
                        ->get();

                    // Partition services into current and previous
                    $partitionedData = $combinedData->partition(function ($item) {
                        return $item->currentService == 1 && is_null($item->releasedDate);
                    });

                    // Get distinct current services (no ranks)
                    $currentService = $partitionedData[0]
                        ->unique('userServiceId')
                        ->map(function ($item) {
                            $servicePeriod = "from {$item->appointedDate} to " . ($item->releasedDate ?? 'present');
                            return [
                                'userServiceId' => $item->userServiceId,
                                'formattedService' => "{$item->serviceName} {$servicePeriod}",
                            ];
                        }); // Keep as a collection

                    // Extract current service IDs
                    $currentServiceIds = $currentService->pluck('userServiceId');

                    // If you need to convert $currentService to an array for Blade:
                    $currentServiceArray = $currentService->pluck('formattedService', 'userServiceId')->toArray();


                    $previousServices = $partitionedData[1]
                    ->unique('userServiceId')
                    ->map(function ($item) {
                        $servicePeriod = "from {$item->appointedDate} to " . ($item->releasedDate ?? 'present');
                        return [
                            'userServiceId' => $item->userServiceId,
                            'formattedService' => "{$item->serviceName} {$servicePeriod}",
                        ];
                    }); // Keep as a collection

                    $previousServiceIds = $previousServices->pluck('userServiceId');

                    // If you need to convert $previousServices to an array for Blade:
                    $previousServicesArray = $previousServices->pluck('formattedService', 'userServiceId')->toArray();


                    $currentServiceRanks = $combinedData->filter(function ($item) use ($currentServiceIds) {
                        return $currentServiceIds->contains($item->userServiceId) && !is_null($item->serviceRankId);
                    })->map(function ($item) {
                        $rankPeriod = "from {$item->rankedDate}";
                        return [
                            'userServiceId' => $item->userServiceId,
                            'formattedRank' => "{$item->rank} {$rankPeriod}",
                        ];
                    });

                    // Convert to an array for Blade if needed
                    $currentServiceRanksArray = $currentServiceRanks->pluck('formattedRank', 'userServiceId')->toArray();

                    $previousServiceRanks = $combinedData->filter(function ($item) use ($previousServiceIds) {
                        return $previousServiceIds->contains($item->userServiceId) && !is_null($item->serviceRankId);
                    })->map(function ($item) {
                        $rankPeriod = "from {$item->rankedDate}";
                        return [
                            'userServiceId' => $item->userServiceId,
                            'formattedRank' => "{$item->rank} {$rankPeriod}",
                        ];
                    });

                    // Convert to an array for Blade if needed
                    $previousServiceRanksArray = $previousServiceRanks->pluck('formattedRank', 'userServiceId')->toArray();


                    // Fetch appointments and categorize them into current and previous based on the service IDs
                    $appointments = UserServiceAppointment::join('work_places', 'user_service_appointments.workPlaceId', '=', 'work_places.id')
                    ->whereIn('user_service_appointments.userServiceId', $currentServiceIds)
                    ->orWhereIn('user_service_appointments.userServiceId', $previousServiceIds)
                    ->select(
                        'user_service_appointments.*',
                        'work_places.name AS workPlaceName',
                        'work_places.censusNo AS censusNo',
                        'work_places.categoryId AS workPlaceCategory'
                    )
                    ->get();

                    // Partition appointments into categories based on their attributes
                    $appointmentsPartitioned = $appointments->groupBy(function ($appointment) {
                    if ($appointment->current == 1 && is_null($appointment->releasedDate)) {
                        return $appointment->appointmentType == 1 ? 'currentAppointments' : 'currentAttachAppointments';
                    } elseif ($appointment->current == 0 && !is_null($appointment->releasedDate)) {
                        return $appointment->appointmentType == 1 ? 'previousAppointments' : 'previousAttachAppointments';
                    }
                    return null; // Ignore other cases
                    });

                    // Map the partitions to IDs
                    $currentAppointmentIds = $appointmentsPartitioned->get('currentAppointments', collect())->pluck('id')->toArray();
                    $previousAppointmentIds = $appointmentsPartitioned->get('previousAppointments', collect())->pluck('id')->toArray();
                    $currentAttachAppointmentIds = $appointmentsPartitioned->get('currentAttachAppointments', collect())->pluck('id')->toArray();
                    $previousAttachAppointmentIds = $appointmentsPartitioned->get('previousAttachAppointments', collect())->pluck('id')->toArray();

                    // Format and return results for each category
                    $currentAppointments = $appointmentsPartitioned->get('currentAppointments', collect())
                    ->map(function ($appointment) {
                        return [
                            'id' => $appointment->id,
                            'formattedAppointment' => "{$appointment->workPlaceName} from {$appointment->appointedDate}",
                        ];
                    })->pluck('formattedAppointment', 'id')->toArray();

                    $previousAppointments = $appointmentsPartitioned->get('previousAppointments', collect())
                    ->map(function ($appointment) {
                        return [
                            'id' => $appointment->id,
                            'formattedAppointment' => "{$appointment->workPlaceName} from {$appointment->appointedDate} to {$appointment->releasedDate}",
                        ];
                    })->pluck('formattedAppointment', 'id')->toArray();

                    $currentAttachAppointments = $appointmentsPartitioned->get('currentAttachAppointments', collect())
                    ->map(function ($appointment) {
                        return [
                            'id' => $appointment->id,
                            'formattedAppointment' => "{$appointment->workPlaceName} from {$appointment->appointedDate}",
                        ];
                    })->pluck('formattedAppointment', 'id')->toArray();

                    $previousAttachAppointments = $appointmentsPartitioned->get('previousAttachAppointments', collect())
                    ->map(function ($appointment) {
                        return [
                            'id' => $appointment->id,
                            'formattedAppointment' => "{$appointment->workPlaceName} from {$appointment->appointedDate} to {$appointment->releasedDate}",
                        ];
                    })->pluck('formattedAppointment', 'id')->toArray();


                    $positions = UserServiceAppointmentPosition::join('positions', 'user_service_appointment_positions.positionId', '=', 'positions.id')
                        ->whereIn('user_service_appointment_positions.userServiceAppointmentId', array_merge(
                            $currentAppointmentIds,
                            $previousAppointmentIds,
                            $currentAttachAppointmentIds,
                            $previousAttachAppointmentIds
                        ))
                        ->select(
                            'user_service_appointment_positions.*',
                            'positions.name AS position'
                        )
                        ->get();

                    // Partition positions into categories based on appointment IDs
                    $positionsPartitioned = $positions->groupBy(function ($position) use (
                        $currentAppointmentIds,
                        $previousAppointmentIds,
                        $currentAttachAppointmentIds,
                        $previousAttachAppointmentIds,
                    ) {
                        if (in_array($position->userServiceAppointmentId, $currentAppointmentIds)) {
                            return 'currentPositions';
                        } elseif (in_array($position->userServiceAppointmentId, $previousAppointmentIds)) {
                            return 'previousPositions';
                        } elseif (in_array($position->userServiceAppointmentId, $currentAttachAppointmentIds)) {
                            return 'currentAttachPositions';
                        } elseif (in_array($position->userServiceAppointmentId, $previousAttachAppointmentIds)) {
                            return 'previousAttachPositions';
                        }
                        return null; // Ignore other cases
                    });

                    // Map the partitions to structured data
                    $currentPositions = $positionsPartitioned->get('currentPositions', collect())
                        ->map(function ($position) {
                            return [
                                'id' => $position->id,
                                'positionName' => $position->position,
                                'details' => $position->toArray(),
                            ];
                    })->values();

                    $previousPositions = $positionsPartitioned->get('previousPositions', collect())
                        ->map(function ($position) {
                            return [
                                'id' => $position->id,
                                'positionName' => $position->position,
                                'details' => $position->toArray(),
                            ];
                    })->values();

                    $currentAttachPositions = $positionsPartitioned->get('currentAttachPositions', collect())
                        ->map(function ($position) {
                            return [
                                'id' => $position->id,
                                'positionName' => $position->position,
                                'details' => $position->toArray(),
                            ];
                    })->values();

                    $previousAttachPositions = $positionsPartitioned->get('previousAttachPositions', collect())
                        ->map(function ($position) {
                            return [
                                'id' => $position->id,
                                'positionName' => $position->position,
                                'details' => $position->toArray(),
                            ];
                    })->values();


                    $educationQualifications = EducationQualification::join('education_qualification_infos', 'education_qualification_infos.educationQualificationId', '=', 'education_qualifications.id')
                    ->where('education_qualification_infos.userId', Auth::user()->id)
                    ->where('education_qualification_infos.active', 1)
                    ->where('education_qualifications.active', 1)
                    ->selectRaw("GROUP_CONCAT(CONCAT(education_qualifications.name, ' Effective from ', education_qualification_infos.effectiveDate) SEPARATOR '\n') as formattedOutput")
                    ->pluck('formattedOutput')
                    ->first();


                    $professionalQualifications = professionalQualification::join('professional_qualification_infos', 'professional_qualification_infos.professionalQualificationId', '=', 'professional_qualifications.id')
                    ->where('professional_qualification_infos.userId', Auth::user()->id)
                    ->where('professional_qualification_infos.active', 1)
                    ->where('professional_qualifications.active', 1)
                    ->selectRaw("GROUP_CONCAT(CONCAT(professional_qualifications.name, ' Effective from ', professional_qualification_infos.effectiveDate) SEPARATOR '\n') as formattedOutput")
                    ->pluck('formattedOutput')
                    ->first();

                    $family = FamilyInfo::join('family_member_types', 'family_infos.memberType', '=', 'family_member_types.id')
                    ->where('family_infos.userId', Auth::user()->id)
                    ->where('family_infos.active', 1)
                    ->selectRaw("GROUP_CONCAT(CONCAT(family_infos.name, ' ( ', family_infos.nic, ' ', family_member_types.name, ' ', family_infos.profession, ' )') SEPARATOR '\n') as formattedOutput")
                    ->pluck('formattedOutput')
                    ->first();



                    //dd($family);
                    return view('profile/myprofile', compact(
                        'user',
                        'currentServiceArray',
                        'previousServicesArray',
                        'currentServiceRanksArray',
                        'previousServiceRanksArray',
                        'currentAppointments',
                        'previousAppointments',
                        'currentAttachAppointments',
                        'previousAttachAppointments',
                        'currentPositions',
                        'previousPositions',
                        'currentAttachPositions',
                        'previousAttachPositions',
                        'educationQualifications',
                        'professionalQualifications',
                        'family',
                        'option'
                    ));


                }catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
                    // Redirect to the search page or show an error message for invalid ID
                    return redirect()->route('teacher.search')->with('error', 'Invalid teacher ID provided.');
                }

            }else{
                return redirect()->route('dashboard');
            }
        }
    }

    public function myprofileedit(Request $request)
    {
        //dd($request->category);
        //dd(Auth::check(), Auth::user(), Auth::id());

        //dd($request->has('category'), $request->input('category'));

        if (Auth::check() && $request->has('category')) {
            try{

                $option = [
                    'Dashboard' => 'dashboard',
                    'My Profile' => route('profile.myprofile'),
                    'My Profile Edit' => htmlspecialchars_decode(route('profile.myprofileedit',['category' => $request->category])),
                ];

                $category = $request->category;

                $user = User::find(Auth::user()->id);

                $races = collect([]);
                $religions = collect([]);
                $civilStatuses = collect([]);
                $bloodGroups = collect([]);
                $illnesses = collect([]);

                $personal_infos = null;

                if($category == 'personal')
                {
                    $races = Race::where('active', 1)->get();
                    $religions = Religion::where('active', 1)->get();
                    $civilStatuses = CivilStatus::where('active', 1)->get();
                    $bloodGroups = BloodGroup::where('active', 1)->get();
                    $illnesses = Illness::where('active', 1)->get();

                    $personal_infos = DB::table('personal_infos')
                    ->leftJoin('races', function ($join) {
                        $join->on('personal_infos.raceId', '=', 'races.id')
                            ->where('races.active', 1);
                    })
                    ->leftJoin('religions', function ($join) {
                        $join->on('personal_infos.religionId', '=', 'religions.id')
                            ->where('religions.active', 1);
                    })
                    ->leftJoin('civil_statuses', function ($join) {
                        $join->on('personal_infos.civilStatusId', '=', 'civil_statuses.id')
                            ->where('civil_statuses.active', 1);
                    })
                    ->leftJoin('blood_groups', function ($join) {
                        $join->on('personal_infos.bloodGroupId', '=', 'blood_groups.id')
                            ->where('blood_groups.active', 1);
                    })
                    ->leftJoin('illnesses', function ($join) {
                        $join->on('personal_infos.illnessId', '=', 'illnesses.id')
                            ->where('illnesses.active', 1);
                    })
                    ->where('personal_infos.active', 1)
                    ->where('personal_infos.userId', Auth::user()->id)
                    ->select(
                        'personal_infos.birthDay',
                        'races.name as race',
                        'religions.name as religion',
                        'civil_statuses.name as civil_status',
                        'blood_groups.name as blood_group',
                        'illnesses.name as illness'
                    )
                    ->first();
                }

                $contact_infos = null;
                if($category == 'contact')
                {

                    $contact_infos = DB::table('contact_infos')
                    ->where('active', 1)
                    ->where('userId', Auth::id())
                    ->first();
                    //dd($contact_infos);
                }

                $location_info_educations = null;
                $location_info_positions = null;
                if($category == 'location')
                {
                    $location_info_educations = DB::table('location_infos')
                    ->join('offices as division', 'location_infos.educationDivisionId', '=', 'division.id')
                    ->join('offices as zone', 'division.higherOfficeId', '=', 'zone.id')
                    ->join('offices as province', 'zone.higherOfficeId', '=', 'province.id')
                    ->join('work_places as division_wp', 'division.workPlaceId', '=', 'division_wp.id')
                    ->join('work_places as zone_wp', 'zone.workPlaceId', '=', 'zone_wp.id')
                    ->join('work_places as province_wp', 'province.workPlaceId', '=', 'province_wp.id')
                    ->select(
                        'location_infos.*',
                        // 'division.name as division_name',
                        // 'zone.name as zone_name',
                        // 'province.name as province_name',
                        'division_wp.name as division',
                        'zone_wp.name as zone',
                        'province_wp.name as department'
                    )
                    ->where('location_infos.active', 1) // optional: filter active
                    ->where('location_infos.userId', Auth::id())
                    ->first();
                    //dd($location_infos);

                    $location_info_positions = DB::table('location_infos')
                        ->join('gn_divisions', 'location_infos.gnDivisionId', '=', 'gn_divisions.id')
                        ->join('ds_divisions', 'gn_divisions.dsId', '=', 'ds_divisions.id')
                        ->join('districts', 'ds_divisions.districtId', '=', 'districts.id')
                        ->join('provinces', 'districts.provinceId', '=', 'provinces.id')
                        ->select(
                            'location_infos.*',
                            'gn_divisions.name as gn_division',
                            'gn_divisions.gnCode',
                            'ds_divisions.name as ds_division',
                            'districts.name as district',
                            'provinces.name as province'
                        )
                        ->where('location_infos.active', 1)
                        ->where('location_infos.userId', Auth::id())
                        ->first();
                }


                $services = collect([]);
                $current_service_infos = collect([]);
                $previous_service_infos = collect([]);

                // if($category == 'service')
                // {
                //     $services = Service::where('active', 1)->get();
                //     $service_infos = DB::table('user_in_services')
                //         ->join('services', 'user_in_services.serviceId', '=', 'services.id')
                //         ->where('user_in_services.userId', Auth::id())
                //         ->select('user_in_services.id AS id', 'user_in_services.*', 'services.name AS name') // adjust fields as needed
                //         ->get();

                //         // Divide into two arrays
                //         $current_service_infos = $service_infos->filter(function ($item) {
                //             return is_null($item->releasedDate);
                //         })->values();

                //         $previous_service_infos = $service_infos->filter(function ($item) {
                //             return !is_null($item->releasedDate);
                //         })->values();
                //     //dd($service_infos);
                // }

                $appointment_lists = collect([]);
                if($category == 'appointment')
                {
                    $appointment_lists = DB::table('user_in_services')
                    ->join('services', 'user_in_services.serviceId', '=', 'services.id')
                    ->join('user_service_appointments', 'user_service_appointments.userServiceId', '=', 'user_in_services.id')
                    ->join('work_places', 'work_places.id', '=', 'user_service_appointments.workPlaceId')
                    ->where('user_in_services.userId', $user->id)
                    ->where('user_in_services.active', 1)
                    ->where('services.active', 1)
                    ->where('user_service_appointments.active', 1)
                    ->where('work_places.active', 1)
                    ->whereNotNull('user_service_appointments.releasedDate')
                    ->select(
                        'user_service_appointments.id as id',
                        DB::raw("CONCAT(services.name, ' | ', work_places.name, ' | ', user_service_appointments.appointedDate, ' - ', user_service_appointments.releasedDate) as name")
                    )
                    ->get();

                }

                $educationQualifications = collect([]);
                $professionalQualifications = collect([]);
                if($category == 'qualification')
                {
                    $educationQualifications = EducationQualification::where('active', 1)->get();
                    $professionalQualifications = ProfessionalQualification::where('active', 1)->get();
                }

                $ranks = collect([]);
                if($category == 'rank')
                {
                    $ranks = Rank::where('active', 1)
                    ->where('serviceId', 1)
                    ->get();

                }

                return view('profile/myprofileedit', compact(
                    'user',
                    'category',
                    'races',
                    'religions',
                    'civilStatuses',
                    'bloodGroups',
                    'illnesses',
                    'personal_infos',
                    'contact_infos',
                    'location_info_educations',
                    'location_info_positions',
                    'services',
                    'current_service_infos',
                    'previous_service_infos',
                    'appointment_lists',
                    'educationQualifications',
                    'professionalQualifications',
                    'ranks',
                    'option'
                ));


            }catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
                // Redirect to the search page or show an error message for invalid ID
                return redirect()->route('profile.myprofile')->with('error', 'Invalid User ID provided.');
            }

        }else{
            dd('adad');
            //return redirect()->route('teacher.search');
        }
    }

    public function myappointment()
    {
        //dd(session('serviceId'));
        if(session('serviceId')){
            if(Auth::user()->id){
                try{

                    $option = [
                        'Dashboard' => 'dashboard',
                        'My Profile' => route('profile.myprofile'),
                    ];

                    $services = UserInService::join('services', 'user_in_services.serviceId', '=', 'services.id')
                        ->leftJoin('user_service_in_ranks', 'user_in_services.id', '=', 'user_service_in_ranks.userServiceId')
                        ->leftJoin('ranks', 'user_service_in_ranks.rankId', '=', 'ranks.id')
                        ->where('user_in_services.userId', Auth::user()->id)
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
                            'ranks.name AS rank'
                        )
                        ->get();

                    // Partition services into current and previous
                    $partitionedData = $services->partition(function ($item) {
                        return $item->currentService == 1 && is_null($item->releasedDate);
                    });

                    // Get distinct current services (no ranks)
                    $currentService = $partitionedData[0]
                        ->unique('userServiceId')
                        ->map(function ($item) {
                            $servicePeriod = "from {$item->appointedDate} to " . ($item->releasedDate ?? 'present');
                            return [
                                'userServiceId' => $item->userServiceId,
                                'formattedService' => "{$item->serviceName} {$servicePeriod}",
                            ];
                        }); // Keep as a collection

                    // Extract current service IDs
                    $currentServiceIds = $currentService->pluck('userServiceId');

                    // If you need to convert $currentService to an array for Blade:
                    $currentServiceArray = $currentService->pluck('formattedService', 'userServiceId')->toArray();


                    $previousServices = $partitionedData[1]
                    ->unique('userServiceId')
                    ->map(function ($item) {
                        $servicePeriod = "from {$item->appointedDate} to " . ($item->releasedDate ?? 'present');
                        return [
                            'userServiceId' => $item->userServiceId,
                            'formattedService' => "{$item->serviceName} {$servicePeriod}",
                        ];
                    }); // Keep as a collection

                    $previousServiceIds = $previousServices->pluck('userServiceId');

                    // If you need to convert $previousServices to an array for Blade:
                    $previousServicesArray = $previousServices->pluck('formattedService', 'userServiceId')->toArray();


                    $currentServiceRanks = $combinedData->filter(function ($item) use ($currentServiceIds) {
                        return $currentServiceIds->contains($item->userServiceId) && !is_null($item->serviceRankId);
                    })->map(function ($item) {
                        $rankPeriod = "from {$item->rankedDate}";
                        return [
                            'userServiceId' => $item->userServiceId,
                            'formattedRank' => "{$item->rank} {$rankPeriod}",
                        ];
                    });

                    // Convert to an array for Blade if needed
                    $currentServiceRanksArray = $currentServiceRanks->pluck('formattedRank', 'userServiceId')->toArray();

                    $previousServiceRanks = $combinedData->filter(function ($item) use ($previousServiceIds) {
                        return $previousServiceIds->contains($item->userServiceId) && !is_null($item->serviceRankId);
                    })->map(function ($item) {
                        $rankPeriod = "from {$item->rankedDate}";
                        return [
                            'userServiceId' => $item->userServiceId,
                            'formattedRank' => "{$item->rank} {$rankPeriod}",
                        ];
                    });

                    // Convert to an array for Blade if needed
                    $previousServiceRanksArray = $previousServiceRanks->pluck('formattedRank', 'userServiceId')->toArray();


                    // Fetch appointments and categorize them into current and previous based on the service IDs
                    $appointments = UserServiceAppointment::join('work_places', 'user_service_appointments.workPlaceId', '=', 'work_places.id')
                    ->whereIn('user_service_appointments.userServiceId', $currentServiceIds)
                    ->orWhereIn('user_service_appointments.userServiceId', $previousServiceIds)
                    ->select(
                        'user_service_appointments.*',
                        'work_places.name AS workPlaceName',
                        'work_places.censusNo AS censusNo',
                        'work_places.categoryId AS workPlaceCategory'
                    )
                    ->get();

                    // Partition appointments into categories based on their attributes
                    $appointmentsPartitioned = $appointments->groupBy(function ($appointment) {
                    if ($appointment->current == 1 && is_null($appointment->releasedDate)) {
                        return $appointment->appointmentType == 1 ? 'currentAppointments' : 'currentAttachAppointments';
                    } elseif ($appointment->current == 0 && !is_null($appointment->releasedDate)) {
                        return $appointment->appointmentType == 1 ? 'previousAppointments' : 'previousAttachAppointments';
                    }
                    return null; // Ignore other cases
                    });

                    // Map the partitions to IDs
                    $currentAppointmentIds = $appointmentsPartitioned->get('currentAppointments', collect())->pluck('id')->toArray();
                    $previousAppointmentIds = $appointmentsPartitioned->get('previousAppointments', collect())->pluck('id')->toArray();
                    $currentAttachAppointmentIds = $appointmentsPartitioned->get('currentAttachAppointments', collect())->pluck('id')->toArray();
                    $previousAttachAppointmentIds = $appointmentsPartitioned->get('previousAttachAppointments', collect())->pluck('id')->toArray();

                    // Format and return results for each category
                    $currentAppointments = $appointmentsPartitioned->get('currentAppointments', collect())
                    ->map(function ($appointment) {
                        return [
                            'id' => $appointment->id,
                            'formattedAppointment' => "{$appointment->workPlaceName} from {$appointment->appointedDate}",
                        ];
                    })->pluck('formattedAppointment', 'id')->toArray();

                    $previousAppointments = $appointmentsPartitioned->get('previousAppointments', collect())
                    ->map(function ($appointment) {
                        return [
                            'id' => $appointment->id,
                            'formattedAppointment' => "{$appointment->workPlaceName} from {$appointment->appointedDate} to {$appointment->releasedDate}",
                        ];
                    })->pluck('formattedAppointment', 'id')->toArray();

                    $currentAttachAppointments = $appointmentsPartitioned->get('currentAttachAppointments', collect())
                    ->map(function ($appointment) {
                        return [
                            'id' => $appointment->id,
                            'formattedAppointment' => "{$appointment->workPlaceName} from {$appointment->appointedDate}",
                        ];
                    })->pluck('formattedAppointment', 'id')->toArray();

                    $previousAttachAppointments = $appointmentsPartitioned->get('previousAttachAppointments', collect())
                    ->map(function ($appointment) {
                        return [
                            'id' => $appointment->id,
                            'formattedAppointment' => "{$appointment->workPlaceName} from {$appointment->appointedDate} to {$appointment->releasedDate}",
                        ];
                    })->pluck('formattedAppointment', 'id')->toArray();


                    $positions = UserServiceAppointmentPosition::join('positions', 'user_service_appointment_positions.positionId', '=', 'positions.id')
                        ->whereIn('user_service_appointment_positions.userServiceAppointmentId', array_merge(
                            $currentAppointmentIds,
                            $previousAppointmentIds,
                            $currentAttachAppointmentIds,
                            $previousAttachAppointmentIds
                        ))
                        ->select(
                            'user_service_appointment_positions.*',
                            'positions.name AS position'
                        )
                        ->get();

                    // Partition positions into categories based on appointment IDs
                    $positionsPartitioned = $positions->groupBy(function ($position) use (
                        $currentAppointmentIds,
                        $previousAppointmentIds,
                        $currentAttachAppointmentIds,
                        $previousAttachAppointmentIds,
                    ) {
                        if (in_array($position->userServiceAppointmentId, $currentAppointmentIds)) {
                            return 'currentPositions';
                        } elseif (in_array($position->userServiceAppointmentId, $previousAppointmentIds)) {
                            return 'previousPositions';
                        } elseif (in_array($position->userServiceAppointmentId, $currentAttachAppointmentIds)) {
                            return 'currentAttachPositions';
                        } elseif (in_array($position->userServiceAppointmentId, $previousAttachAppointmentIds)) {
                            return 'previousAttachPositions';
                        }
                        return null; // Ignore other cases
                    });

                    // Map the partitions to structured data
                    $currentPositions = $positionsPartitioned->get('currentPositions', collect())
                        ->map(function ($position) {
                            return [
                                'id' => $position->id,
                                'positionName' => $position->position,
                                'details' => $position->toArray(),
                            ];
                    })->values();

                    $previousPositions = $positionsPartitioned->get('previousPositions', collect())
                        ->map(function ($position) {
                            return [
                                'id' => $position->id,
                                'positionName' => $position->position,
                                'details' => $position->toArray(),
                            ];
                    })->values();

                    $currentAttachPositions = $positionsPartitioned->get('currentAttachPositions', collect())
                        ->map(function ($position) {
                            return [
                                'id' => $position->id,
                                'positionName' => $position->position,
                                'details' => $position->toArray(),
                            ];
                    })->values();

                    $previousAttachPositions = $positionsPartitioned->get('previousAttachPositions', collect())
                        ->map(function ($position) {
                            return [
                                'id' => $position->id,
                                'positionName' => $position->position,
                                'details' => $position->toArray(),
                            ];
                    })->values();


                    $educationQualifications = EducationQualification::join('education_qualification_infos', 'education_qualification_infos.educationQualificationId', '=', 'education_qualifications.id')
                    ->where('education_qualification_infos.userId', Auth::user()->id)
                    ->where('education_qualification_infos.active', 1)
                    ->where('education_qualifications.active', 1)
                    ->selectRaw("GROUP_CONCAT(CONCAT(education_qualifications.name, ' Effective from ', education_qualification_infos.effectiveDate) SEPARATOR '\n') as formattedOutput")
                    ->pluck('formattedOutput')
                    ->first();


                    $professionalQualifications = professionalQualification::join('professional_qualification_infos', 'professional_qualification_infos.professionalQualificationId', '=', 'professional_qualifications.id')
                    ->where('professional_qualification_infos.userId', Auth::user()->id)
                    ->where('professional_qualification_infos.active', 1)
                    ->where('professional_qualifications.active', 1)
                    ->selectRaw("GROUP_CONCAT(CONCAT(professional_qualifications.name, ' Effective from ', professional_qualification_infos.effectiveDate) SEPARATOR '\n') as formattedOutput")
                    ->pluck('formattedOutput')
                    ->first();

                    $family = FamilyInfo::join('family_member_types', 'family_infos.memberType', '=', 'family_member_types.id')
                    ->where('family_infos.userId', Auth::user()->id)
                    ->where('family_infos.active', 1)
                    ->selectRaw("GROUP_CONCAT(CONCAT(family_infos.name, ' ( ', family_infos.nic, ' ', family_member_types.name, ' ', family_infos.profession, ' )') SEPARATOR '\n') as formattedOutput")
                    ->pluck('formattedOutput')
                    ->first();



                    //dd($family);
                    return view('profile/myprofile', compact(
                        'user',
                        'currentServiceArray',
                        'previousServicesArray',
                        'currentServiceRanksArray',
                        'previousServiceRanksArray',
                        'currentAppointments',
                        'previousAppointments',
                        'currentAttachAppointments',
                        'previousAttachAppointments',
                        'currentPositions',
                        'previousPositions',
                        'currentAttachPositions',
                        'previousAttachPositions',
                        'educationQualifications',
                        'professionalQualifications',
                        'family',
                        'option'
                    ));


                }catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
                    // Redirect to the search page or show an error message for invalid ID
                    return redirect()->route('teacher.search')->with('error', 'Invalid teacher ID provided.');
                }

            }else{
                return redirect()->route('teacher.search');
            }
        }
    }

    public function teacherindex()
    {
        $teacherQuery = DB::table('users')
            ->join('personal_infos', function ($join) {
                $join->on('personal_infos.userId', '=', 'users.id')
                    ->where('personal_infos.active', 1); // Ensure personal_infos is active
            })
            ->join('user_in_services', function ($join) {
                $join->on('user_in_services.userId', '=', 'users.id')
                    ->where('user_in_services.active', 1) // Ensure user_in_services is active
                    ->where('user_in_services.current', 1) // Ensure user_in_services is current
                    ->where('user_in_services.serviceId', 1);
            })
            ->join('user_service_appointments', function ($join) {
                $join->on('user_service_appointments.userServiceId', '=', 'user_in_services.id')
                    ->where('user_service_appointments.active', 1) // Ensure user_service_appointments is active
                    ->where('user_service_appointments.current', 1); // Ensure user_service_appointments is current
            })
            ->join('work_places', function ($join) {
                $join->on('user_service_appointments.workPlaceId', '=', 'work_places.id')
                    ->where('work_places.active', 1); // Ensure work_places is active
            });

        if (session('schoolId')) {
            $teacherQuery->where('work_places.id', session('workPlaceId'));
        } elseif (session('officeId') && session('officeTypeId') == 3) {
            $teacherQuery->join('schools', function ($join) {
                $join->on('work_places.id', '=', 'schools.workPlaceId')
                    ->where('schools.active', 1); // Ensure schools is active
            })->where('schools.officeId', session('officeId'));
        } elseif (session('officeId') && session('officeTypeId') == 2) {
            $teacherQuery->join('schools', function ($join) {
                $join->on('work_places.id', '=', 'schools.workPlaceId')
                    ->where('schools.active', 1); // Ensure schools is active
            })
            ->join('offices', function ($join) {
                $join->on('schools.officeId', '=', 'offices.id')
                    ->where('offices.active', 1); // Ensure offices is active
            })
            ->where('offices.higherOfficeId', session('officeId'));
        } elseif (session('officeId') && session('officeTypeId') == 1) {
            $teacherQuery->join('schools', function ($join) {
                $join->on('work_places.id', '=', 'schools.workPlaceId')
                    ->where('schools.active', 1); // Ensure schools is active
            })
            ->join('offices AS divisions', function ($join) {
                $join->on('schools.officeId', '=', 'divisions.id')
                    ->where('divisions.active', 1); // Ensure divisions is active
            })
            ->join('offices AS zones', function ($join) {
                $join->on('divisions.higherOfficeId', '=', 'zones.id')
                    ->where('zones.active', 1); // Ensure zones is active
            })
            ->where('zones.higherOfficeId', session('officeId'));
        }

        // Age groups and gender counts
        $teacherCounts = $teacherQuery
            ->select(
                DB::raw('SUM(CASE WHEN personal_infos.genderId = 1 AND TIMESTAMPDIFF(YEAR, personal_infos.birthDay, CURDATE()) BETWEEN 20 AND 30 THEN 1 ELSE 0 END) as male_20_30'),
                DB::raw('SUM(CASE WHEN personal_infos.genderId = 2 AND TIMESTAMPDIFF(YEAR, personal_infos.birthDay, CURDATE()) BETWEEN 20 AND 30 THEN 1 ELSE 0 END) as female_20_30'),

                DB::raw('SUM(CASE WHEN personal_infos.genderId = 1 AND TIMESTAMPDIFF(YEAR, personal_infos.birthDay, CURDATE()) BETWEEN 30 AND 40 THEN 1 ELSE 0 END) as male_30_40'),
                DB::raw('SUM(CASE WHEN personal_infos.genderId = 2 AND TIMESTAMPDIFF(YEAR, personal_infos.birthDay, CURDATE()) BETWEEN 30 AND 40 THEN 1 ELSE 0 END) as female_30_40'),

                DB::raw('SUM(CASE WHEN personal_infos.genderId = 1 AND TIMESTAMPDIFF(YEAR, personal_infos.birthDay, CURDATE()) BETWEEN 40 AND 50 THEN 1 ELSE 0 END) as male_40_50'),
                DB::raw('SUM(CASE WHEN personal_infos.genderId = 2 AND TIMESTAMPDIFF(YEAR, personal_infos.birthDay, CURDATE()) BETWEEN 40 AND 50 THEN 1 ELSE 0 END) as female_40_50'),

                DB::raw('SUM(CASE WHEN personal_infos.genderId = 1 AND TIMESTAMPDIFF(YEAR, personal_infos.birthDay, CURDATE()) BETWEEN 50 AND 55 THEN 1 ELSE 0 END) as male_50_55'),
                DB::raw('SUM(CASE WHEN personal_infos.genderId = 2 AND TIMESTAMPDIFF(YEAR, personal_infos.birthDay, CURDATE()) BETWEEN 50 AND 55 THEN 1 ELSE 0 END) as female_50_55'),

                DB::raw('SUM(CASE WHEN personal_infos.genderId = 1 AND TIMESTAMPDIFF(YEAR, personal_infos.birthDay, CURDATE()) BETWEEN 55 AND 59 THEN 1 ELSE 0 END) as male_55_59'),
                DB::raw('SUM(CASE WHEN personal_infos.genderId = 2 AND TIMESTAMPDIFF(YEAR, personal_infos.birthDay, CURDATE()) BETWEEN 55 AND 59 THEN 1 ELSE 0 END) as female_55_59'),

                DB::raw('SUM(CASE WHEN personal_infos.genderId = 1 AND TIMESTAMPDIFF(YEAR, personal_infos.birthDay, CURDATE()) BETWEEN 59 AND 60 THEN 1 ELSE 0 END) as male_59_60'),
                DB::raw('SUM(CASE WHEN personal_infos.genderId = 2 AND TIMESTAMPDIFF(YEAR, personal_infos.birthDay, CURDATE()) BETWEEN 59 AND 60 THEN 1 ELSE 0 END) as female_59_60')
            )
            ->get();


        $option = [
            'Dashboard' => 'dashboard',
            'Teacher Dashboard' => 'teacher.dashboard'
        ];

        //dd($card_pack_1);
        return view('teacher/dashboard',compact('option','teacherCounts'));
    }

    public function principalindex()
    {
        $principalQuery = DB::table('users')
            ->join('personal_infos', function ($join) {
                $join->on('personal_infos.userId', '=', 'users.id')
                    ->where('personal_infos.active', 1); // Ensure personal_infos is active
            })
            ->join('user_in_services', function ($join) {
                $join->on('user_in_services.userId', '=', 'users.id')
                    ->where('user_in_services.active', 1) // Ensure user_in_services is active
                    ->where('user_in_services.current', 1) // Ensure user_in_services is current
                    ->where('user_in_services.serviceId', 3);
            })
            ->join('user_service_appointments', function ($join) {
                $join->on('user_service_appointments.userServiceId', '=', 'user_in_services.id')
                    ->where('user_service_appointments.active', 1) // Ensure user_service_appointments is active
                    ->where('user_service_appointments.current', 1); // Ensure user_service_appointments is current
            })
            ->join('work_places', function ($join) {
                $join->on('user_service_appointments.workPlaceId', '=', 'work_places.id')
                    ->where('work_places.active', 1); // Ensure work_places is active
            });

        if (session('schoolId')) {
            $principalQuery->where('work_places.id', session('workPlaceId'));
        } elseif (session('officeId') && session('officeTypeId') == 3) {
            $principalQuery->join('schools', function ($join) {
                $join->on('work_places.id', '=', 'schools.workPlaceId')
                    ->where('schools.active', 1); // Ensure schools is active
            })->where('schools.officeId', session('officeId'));
        } elseif (session('officeId') && session('officeTypeId') == 2) {
            $principalQuery->join('schools', function ($join) {
                $join->on('work_places.id', '=', 'schools.workPlaceId')
                    ->where('schools.active', 1); // Ensure schools is active
            })
            ->join('offices', function ($join) {
                $join->on('schools.officeId', '=', 'offices.id')
                    ->where('offices.active', 1); // Ensure offices is active
            })
            ->where('offices.higherOfficeId', session('officeId'));
        } elseif (session('officeId') && session('officeTypeId') == 1) {
            $principalQuery->join('schools', function ($join) {
                $join->on('work_places.id', '=', 'schools.workPlaceId')
                    ->where('schools.active', 1); // Ensure schools is active
            })
            ->join('offices AS divisions', function ($join) {
                $join->on('schools.officeId', '=', 'divisions.id')
                    ->where('divisions.active', 1); // Ensure divisions is active
            })
            ->join('offices AS zones', function ($join) {
                $join->on('divisions.higherOfficeId', '=', 'zones.id')
                    ->where('zones.active', 1); // Ensure zones is active
            })
            ->where('zones.higherOfficeId', session('officeId'));
        }

        // Age groups and gender counts
        $principalCounts = $principalQuery
            ->select(
                DB::raw('SUM(CASE WHEN personal_infos.genderId = 1 AND TIMESTAMPDIFF(YEAR, personal_infos.birthDay, CURDATE()) BETWEEN 20 AND 30 THEN 1 ELSE 0 END) as male_20_30'),
                DB::raw('SUM(CASE WHEN personal_infos.genderId = 2 AND TIMESTAMPDIFF(YEAR, personal_infos.birthDay, CURDATE()) BETWEEN 20 AND 30 THEN 1 ELSE 0 END) as female_20_30'),

                DB::raw('SUM(CASE WHEN personal_infos.genderId = 1 AND TIMESTAMPDIFF(YEAR, personal_infos.birthDay, CURDATE()) BETWEEN 30 AND 40 THEN 1 ELSE 0 END) as male_30_40'),
                DB::raw('SUM(CASE WHEN personal_infos.genderId = 2 AND TIMESTAMPDIFF(YEAR, personal_infos.birthDay, CURDATE()) BETWEEN 30 AND 40 THEN 1 ELSE 0 END) as female_30_40'),

                DB::raw('SUM(CASE WHEN personal_infos.genderId = 1 AND TIMESTAMPDIFF(YEAR, personal_infos.birthDay, CURDATE()) BETWEEN 40 AND 50 THEN 1 ELSE 0 END) as male_40_50'),
                DB::raw('SUM(CASE WHEN personal_infos.genderId = 2 AND TIMESTAMPDIFF(YEAR, personal_infos.birthDay, CURDATE()) BETWEEN 40 AND 50 THEN 1 ELSE 0 END) as female_40_50'),

                DB::raw('SUM(CASE WHEN personal_infos.genderId = 1 AND TIMESTAMPDIFF(YEAR, personal_infos.birthDay, CURDATE()) BETWEEN 50 AND 55 THEN 1 ELSE 0 END) as male_50_55'),
                DB::raw('SUM(CASE WHEN personal_infos.genderId = 2 AND TIMESTAMPDIFF(YEAR, personal_infos.birthDay, CURDATE()) BETWEEN 50 AND 55 THEN 1 ELSE 0 END) as female_50_55'),

                DB::raw('SUM(CASE WHEN personal_infos.genderId = 1 AND TIMESTAMPDIFF(YEAR, personal_infos.birthDay, CURDATE()) BETWEEN 55 AND 59 THEN 1 ELSE 0 END) as male_55_59'),
                DB::raw('SUM(CASE WHEN personal_infos.genderId = 2 AND TIMESTAMPDIFF(YEAR, personal_infos.birthDay, CURDATE()) BETWEEN 55 AND 59 THEN 1 ELSE 0 END) as female_55_59'),

                DB::raw('SUM(CASE WHEN personal_infos.genderId = 1 AND TIMESTAMPDIFF(YEAR, personal_infos.birthDay, CURDATE()) BETWEEN 59 AND 60 THEN 1 ELSE 0 END) as male_59_60'),
                DB::raw('SUM(CASE WHEN personal_infos.genderId = 2 AND TIMESTAMPDIFF(YEAR, personal_infos.birthDay, CURDATE()) BETWEEN 59 AND 60 THEN 1 ELSE 0 END) as female_59_60')
            )
            ->get();


        $option = [
            'Dashboard' => 'dashboard',
            'Principal Dashboard' => 'principal.dashboard'
        ];

        //dd($card_pack_1);
        return view('principal/dashboard',compact('option','principalCounts'));
    }

    public function sleasindex()
    {
        $teacherQuery = DB::table('users')
            ->join('personal_infos', function ($join) {
                $join->on('personal_infos.userId', '=', 'users.id')
                    ->where('personal_infos.active', 1); // Ensure personal_infos is active
            })
            ->join('user_in_services', function ($join) {
                $join->on('user_in_services.userId', '=', 'users.id')
                    ->where('user_in_services.active', 1) // Ensure user_in_services is active
                    ->where('user_in_services.current', 1) // Ensure user_in_services is current
                    ->where('user_in_services.serviceId', 4);
            })
            ->join('user_service_appointments', function ($join) {
                $join->on('user_service_appointments.userServiceId', '=', 'user_in_services.id')
                    ->where('user_service_appointments.active', 1) // Ensure user_service_appointments is active
                    ->where('user_service_appointments.current', 1); // Ensure user_service_appointments is current
            })
            ->join('work_places', function ($join) {
                $join->on('user_service_appointments.workPlaceId', '=', 'work_places.id')
                    ->where('work_places.active', 1); // Ensure work_places is active
            });

        if (session('schoolId')) {
            $teacherQuery->where('work_places.id', session('workPlaceId'));
        } elseif (session('officeId') && session('officeTypeId') == 3) {
            $teacherQuery->join('schools', function ($join) {
                $join->on('work_places.id', '=', 'schools.workPlaceId')
                    ->where('schools.active', 1); // Ensure schools is active
            })->where('schools.officeId', session('officeId'));
        } elseif (session('officeId') && session('officeTypeId') == 2) {
            $teacherQuery->join('schools', function ($join) {
                $join->on('work_places.id', '=', 'schools.workPlaceId')
                    ->where('schools.active', 1); // Ensure schools is active
            })
            ->join('offices', function ($join) {
                $join->on('schools.officeId', '=', 'offices.id')
                    ->where('offices.active', 1); // Ensure offices is active
            })
            ->where('offices.higherOfficeId', session('officeId'));
        } elseif (session('officeId') && session('officeTypeId') == 1) {
            $teacherQuery->join('schools', function ($join) {
                $join->on('work_places.id', '=', 'schools.workPlaceId')
                    ->where('schools.active', 1); // Ensure schools is active
            })
            ->join('offices AS divisions', function ($join) {
                $join->on('schools.officeId', '=', 'divisions.id')
                    ->where('divisions.active', 1); // Ensure divisions is active
            })
            ->join('offices AS zones', function ($join) {
                $join->on('divisions.higherOfficeId', '=', 'zones.id')
                    ->where('zones.active', 1); // Ensure zones is active
            })
            ->where('zones.higherOfficeId', session('officeId'));
        }

        // Age groups and gender counts
        $sleasCounts = $teacherQuery
            ->select(
                DB::raw('SUM(CASE WHEN personal_infos.genderId = 1 AND TIMESTAMPDIFF(YEAR, personal_infos.birthDay, CURDATE()) BETWEEN 20 AND 30 THEN 1 ELSE 0 END) as male_20_30'),
                DB::raw('SUM(CASE WHEN personal_infos.genderId = 2 AND TIMESTAMPDIFF(YEAR, personal_infos.birthDay, CURDATE()) BETWEEN 20 AND 30 THEN 1 ELSE 0 END) as female_20_30'),

                DB::raw('SUM(CASE WHEN personal_infos.genderId = 1 AND TIMESTAMPDIFF(YEAR, personal_infos.birthDay, CURDATE()) BETWEEN 30 AND 40 THEN 1 ELSE 0 END) as male_30_40'),
                DB::raw('SUM(CASE WHEN personal_infos.genderId = 2 AND TIMESTAMPDIFF(YEAR, personal_infos.birthDay, CURDATE()) BETWEEN 30 AND 40 THEN 1 ELSE 0 END) as female_30_40'),

                DB::raw('SUM(CASE WHEN personal_infos.genderId = 1 AND TIMESTAMPDIFF(YEAR, personal_infos.birthDay, CURDATE()) BETWEEN 40 AND 50 THEN 1 ELSE 0 END) as male_40_50'),
                DB::raw('SUM(CASE WHEN personal_infos.genderId = 2 AND TIMESTAMPDIFF(YEAR, personal_infos.birthDay, CURDATE()) BETWEEN 40 AND 50 THEN 1 ELSE 0 END) as female_40_50'),

                DB::raw('SUM(CASE WHEN personal_infos.genderId = 1 AND TIMESTAMPDIFF(YEAR, personal_infos.birthDay, CURDATE()) BETWEEN 50 AND 55 THEN 1 ELSE 0 END) as male_50_55'),
                DB::raw('SUM(CASE WHEN personal_infos.genderId = 2 AND TIMESTAMPDIFF(YEAR, personal_infos.birthDay, CURDATE()) BETWEEN 50 AND 55 THEN 1 ELSE 0 END) as female_50_55'),

                DB::raw('SUM(CASE WHEN personal_infos.genderId = 1 AND TIMESTAMPDIFF(YEAR, personal_infos.birthDay, CURDATE()) BETWEEN 55 AND 59 THEN 1 ELSE 0 END) as male_55_59'),
                DB::raw('SUM(CASE WHEN personal_infos.genderId = 2 AND TIMESTAMPDIFF(YEAR, personal_infos.birthDay, CURDATE()) BETWEEN 55 AND 59 THEN 1 ELSE 0 END) as female_55_59'),

                DB::raw('SUM(CASE WHEN personal_infos.genderId = 1 AND TIMESTAMPDIFF(YEAR, personal_infos.birthDay, CURDATE()) BETWEEN 59 AND 60 THEN 1 ELSE 0 END) as male_59_60'),
                DB::raw('SUM(CASE WHEN personal_infos.genderId = 2 AND TIMESTAMPDIFF(YEAR, personal_infos.birthDay, CURDATE()) BETWEEN 59 AND 60 THEN 1 ELSE 0 END) as female_59_60')
            )
            ->get();


        $option = [
            'Dashboard' => 'dashboard',
            'SLEAS Dashboard' => 'sleas.dashboard'
        ];

        //dd($card_pack_1);
        return view('sleas/dashboard',compact('option','sleasCounts'));
    }

    public function teachercreate()
    {
        $subjects = Subject::where('active', 1)->get();
        $appointedMediums = AppointmentMedium::where('active', 1)->get();
        $appointmentCategories = AppointmentCategory::where('active', 1)->get();
        $ranks = Rank::where('active', 1)
            ->where('serviceId', 1) // Filter by serviceId
            ->get();


        $option = [
            'Dashboard' => 'teacher.dashboard',
            'Teacher Registration' => 'teacher.register'
        ];
        return view('teacher/register',compact('option', 'subjects', 'appointedMediums', 'appointmentCategories', 'ranks'));
    }

    public function principalcreate()
    {
        $appointedMediums = AppointmentMedium::where('active', 1)->get();
        $ranks = Rank::where('active', 1)
            ->where('serviceId', 3) // Filter by serviceId
            ->get();


        $option = [
            'Dashboard' => 'principal.dashboard',
            'Principal Registration' => 'principal.register'
        ];
        return view('principal/register',compact('option', 'appointedMediums', 'ranks'));
    }

    public function sleascreate()
    {
        $ranks = Rank::where('active', 1)
            ->where('serviceId', 4) // Filter by serviceId
            ->get();


        $option = [
            'Dashboard' => 'sleas.dashboard',
            'SLEAS Registration' => 'sleas.register'
        ];
        return view('sleas/register',compact('option', 'ranks'));
    }

    public function teacherstore(StoreUserRequest $request)
    {

        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('userphotos', 'public');
            $profileImage = Storage::url($photoPath);
        } else {
            $profileImage = null;
        }

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

        $userServiceInRank = UserServiceInRank::create([
            'userServiceId' => $userInService->id,
            'rankId' => $request->rank,
            'rankedDate' => $request->serviceDate,
        ]);

        $teacherService = TeacherService::create([
            'userServiceId' => $userInService->id,
            'appointmentSubjectId' => $request->subject,
            'mainSubjectId' => $request->subject,
            'appointmentMediumId' => $request->medium,
            'appointmentCategoryId' => $request->category,
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
            'profilePicture' => $profileImage,
            'genderId' => $request->gender,
            'birthDay' => $request->birthDay,
        ]);
        $personalInfo->save();

        $locationInfo = LocationInfo::create([
            'userId' => $user->id,
        ]);

        $position = UserServiceAppointmentPosition::create([
            'userServiceAppointmentId' => $userServiceAppointment->id,
            'positionId' => 1,
            'positionedDate' => $request->serviceDate,
        ]);

        return redirect()->back()->with('success', 'Teacher information saved successfully.');
    }

    public function principalstore(StoreUserRequest $request)
    {

        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('userphotos', 'public');
            $profileImage = Storage::url($photoPath);
        } else {
            $profileImage = null;
        }

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
            'serviceId' => 3,
            'appointedDate' => $request->serviceDate,
        ]);

        $userServiceInRank = UserServiceInRank::create([
            'userServiceId' => $userInService->id,
            'rankId' => $request->rank,
            'rankedDate' => $request->serviceDate,
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
            'profilePicture' => $profileImage,
            'genderId' => $request->gender,
            'birthDay' => $request->birthDay,
        ]);
        $personalInfo->save();

        $locationInfo = LocationInfo::create([
            'userId' => $user->id,
        ]);

        $position = UserServiceAppointmentPosition::create([
            'userServiceAppointmentId' => $userServiceAppointment->id,
            'positionId' => 1,
            'positionedDate' => $request->serviceDate,
        ]);

        return redirect()->back()->with('success', 'Principal information saved successfully.');
    }

    public function sleasstore(StoreUserRequest $request)
    {

        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('userphotos', 'public');
            $profileImage = Storage::url($photoPath);
        } else {
            $profileImage = null;
        }

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
            'serviceId' => 4,
            'appointedDate' => $request->serviceDate,
        ]);

        $userServiceInRank = UserServiceInRank::create([
            'userServiceId' => $userInService->id,
            'rankId' => $request->rank,
            'rankedDate' => $request->serviceDate,
        ]);

        $userServiceAppointment = UserServiceAppointment::create([
            'userServiceId' => $userInService->id,
            'workPlaceId' => $request->workPlace,
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
            'profilePicture' => $profileImage,
            'genderId' => $request->gender,
            'birthDay' => $request->birthDay,
        ]);
        $personalInfo->save();

        $locationInfo = LocationInfo::create([
            'userId' => $user->id,
        ]);

        $position = UserServiceAppointmentPosition::create([
            'userServiceAppointmentId' => $userServiceAppointment->id,
            'positionId' => 4,
            'positionedDate' => $request->serviceDate,
        ]);

        return redirect()->back()->with('success', 'Director information saved successfully.');
    }

    public function teachersearch()
    {
        $option = [
            'Dashboard' => 'dashboard',
            'Teacher Dashboard' => 'teacher.dashboard',
            'Teacher Search' => 'teacher.search'
        ];
        return view('teacher/search',compact('option'));
    }

    public function principalsearch()
    {
        $option = [
            'Dashboard' => 'dashboard',
            'Principal Dashboard' => 'principal.dashboard',
            'Principal Search' => 'principal.search'
        ];
        return view('principal/search',compact('option'));
    }

    public function sleassearch()
    {
        $option = [
            'Dashboard' => 'dashboard',
            'SLEAS Dashboard' => 'sleas.dashboard',
            'SLEAS Search' => 'sleas.search'
        ];
        return view('sleas/search',compact('option'));
    }

    public function teacherprofile(Request $request)
    {
        if($request->has('id')){
            try{

                $option = [
                    'Dashboard' => 'dashboard',
                    'Teacher Dashboard' => 'teacher.dashboard',
                    'Teacher Search' => 'teacher.search',
                    'Teacher Profile' => route('teacher.profile', ['id' => $request->id]),
                ];

                $decryptedId = Crypt::decryptString($request->id);
                //dd($decryptedId);
                $teacher = User::leftjoin('personal_infos', 'users.id', '=', 'personal_infos.userId')
                ->leftjoin('races', 'personal_infos.raceId', '=', 'races.id')
                ->leftjoin('religions', 'personal_infos.religionId', '=', 'religions.id')
                ->leftjoin('civil_statuses', 'personal_infos.civilStatusId', '=', 'civil_statuses.id')
                ->leftjoin('contact_infos', 'users.id', '=', 'contact_infos.userId')
                ->leftjoin('location_infos', 'users.id', '=', 'location_infos.userId')
                //->leftjoin('offices', 'location_infos.educationDivisionId', '=', 'offices.id')
                ->leftjoin('work_places AS educationDivisions', 'location_infos.educationDivisionId', '=', 'educationDivisions.id')
                ->leftjoin('gn_divisions', 'location_infos.gnDivisionId', '=', 'gn_divisions.id')
                ->leftjoin('ds_divisions', 'gn_divisions.dsId', '=', 'ds_divisions.id')
                ->leftjoin('districts', 'ds_divisions.districtId', '=', 'districts.id')
                ->leftjoin('provinces', 'districts.provinceId', '=', 'provinces.id')
                ->where('users.id', $decryptedId)
                ->select(
                    'users.id AS userId','users.name AS name','users.nic','users.email','users.nameWithInitials',
                    'personal_infos.birthDay','personal_infos.profilePicture',
                    DB::raw("CASE
                        WHEN personal_infos.genderId = 1 THEN 'Male'
                        WHEN personal_infos.genderId = 2 THEN 'Female'
                        ELSE 'Unknown'
                    END AS gender"),
                    'races.name AS race',
                    'religions.name AS religion',
                    'civil_statuses.name AS civilStatus',
                    'contact_infos.*',
                    'educationDivisions.name AS educationDivision',
                    'gn_divisions.name AS gnDivision',
                    'ds_divisions.name AS dsDivision',
                    'districts.name AS district',
                    'provinces.name AS province',
                )
                ->first();
                if ($teacher) {
                    $teacher->cryptedId = $request->id;
                }

                $combinedData = UserInService::join('services', 'user_in_services.serviceId', '=', 'services.id')
                    ->leftJoin('user_service_in_ranks', 'user_in_services.id', '=', 'user_service_in_ranks.userServiceId')
                    ->leftJoin('ranks', 'user_service_in_ranks.rankId', '=', 'ranks.id')
                    ->where('user_in_services.userId', $decryptedId)
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
                        'ranks.name AS rank'
                    )
                    ->get();

                // Partition services into current and previous
                $partitionedData = $combinedData->partition(function ($item) {
                    return $item->currentService == 1 && is_null($item->releasedDate);
                });

                // Get distinct current services (no ranks)
                $currentService = $partitionedData[0]
                    ->unique('userServiceId')
                    ->map(function ($item) {
                        $servicePeriod = "from {$item->appointedDate} to " . ($item->releasedDate ?? 'present');
                        return [
                            'userServiceId' => $item->userServiceId,
                            'formattedService' => "{$item->serviceName} {$servicePeriod}",
                        ];
                    }); // Keep as a collection

                // Extract current service IDs
                $currentServiceIds = $currentService->pluck('userServiceId');

                // If you need to convert $currentService to an array for Blade:
                $currentServiceArray = $currentService->pluck('formattedService', 'userServiceId')->toArray();


                $previousServices = $partitionedData[1]
                ->unique('userServiceId')
                ->map(function ($item) {
                    $servicePeriod = "from {$item->appointedDate} to " . ($item->releasedDate ?? 'present');
                    return [
                        'userServiceId' => $item->userServiceId,
                        'formattedService' => "{$item->serviceName} {$servicePeriod}",
                    ];
                }); // Keep as a collection

                $previousServiceIds = $previousServices->pluck('userServiceId');

                // If you need to convert $previousServices to an array for Blade:
                $previousServicesArray = $previousServices->pluck('formattedService', 'userServiceId')->toArray();


                $currentServiceRanks = $combinedData->filter(function ($item) use ($currentServiceIds) {
                    return $currentServiceIds->contains($item->userServiceId) && !is_null($item->serviceRankId);
                })->map(function ($item) {
                    $rankPeriod = "from {$item->rankedDate}";
                    return [
                        'userServiceId' => $item->userServiceId,
                        'formattedRank' => "{$item->rank} {$rankPeriod}",
                    ];
                });

                // Convert to an array for Blade if needed
                $currentServiceRanksArray = $currentServiceRanks->pluck('formattedRank', 'userServiceId')->toArray();

                $previousServiceRanks = $combinedData->filter(function ($item) use ($previousServiceIds) {
                    return $previousServiceIds->contains($item->userServiceId) && !is_null($item->serviceRankId);
                })->map(function ($item) {
                    $rankPeriod = "from {$item->rankedDate}";
                    return [
                        'userServiceId' => $item->userServiceId,
                        'formattedRank' => "{$item->rank} {$rankPeriod}",
                    ];
                });

                // Convert to an array for Blade if needed
                $previousServiceRanksArray = $previousServiceRanks->pluck('formattedRank', 'userServiceId')->toArray();


                // Fetch appointments and categorize them into current and previous based on the service IDs
                $appointments = UserServiceAppointment::join('work_places', 'user_service_appointments.workPlaceId', '=', 'work_places.id')
                ->whereIn('user_service_appointments.userServiceId', $currentServiceIds)
                ->orWhereIn('user_service_appointments.userServiceId', $previousServiceIds)
                ->select(
                    'user_service_appointments.*',
                    'work_places.name AS workPlaceName',
                    'work_places.censusNo AS censusNo',
                    'work_places.categoryId AS workPlaceCategory'
                )
                ->get();

                // Partition appointments into categories based on their attributes
                $appointmentsPartitioned = $appointments->groupBy(function ($appointment) {
                if ($appointment->current == 1 && is_null($appointment->releasedDate)) {
                    return $appointment->appointmentType == 1 ? 'currentAppointments' : 'currentAttachAppointments';
                } elseif ($appointment->current == 0 && !is_null($appointment->releasedDate)) {
                    return $appointment->appointmentType == 1 ? 'previousAppointments' : 'previousAttachAppointments';
                }
                return null; // Ignore other cases
                });

                // Map the partitions to IDs
                $currentAppointmentIds = $appointmentsPartitioned->get('currentAppointments', collect())->pluck('id')->toArray();
                $previousAppointmentIds = $appointmentsPartitioned->get('previousAppointments', collect())->pluck('id')->toArray();
                $currentAttachAppointmentIds = $appointmentsPartitioned->get('currentAttachAppointments', collect())->pluck('id')->toArray();
                $previousAttachAppointmentIds = $appointmentsPartitioned->get('previousAttachAppointments', collect())->pluck('id')->toArray();

                // Format and return results for each category
                $currentAppointments = $appointmentsPartitioned->get('currentAppointments', collect())
                ->map(function ($appointment) {
                    return [
                        'id' => $appointment->id,
                        'formattedAppointment' => "{$appointment->workPlaceName} from {$appointment->appointedDate}",
                    ];
                })->pluck('formattedAppointment', 'id')->toArray();

                $previousAppointments = $appointmentsPartitioned->get('previousAppointments', collect())
                ->map(function ($appointment) {
                    return [
                        'id' => $appointment->id,
                        'formattedAppointment' => "{$appointment->workPlaceName} from {$appointment->appointedDate} to {$appointment->releasedDate}",
                    ];
                })->pluck('formattedAppointment', 'id')->toArray();

                $currentAttachAppointments = $appointmentsPartitioned->get('currentAttachAppointments', collect())
                ->map(function ($appointment) {
                    return [
                        'id' => $appointment->id,
                        'formattedAppointment' => "{$appointment->workPlaceName} from {$appointment->appointedDate}",
                    ];
                })->pluck('formattedAppointment', 'id')->toArray();

                $previousAttachAppointments = $appointmentsPartitioned->get('previousAttachAppointments', collect())
                ->map(function ($appointment) {
                    return [
                        'id' => $appointment->id,
                        'formattedAppointment' => "{$appointment->workPlaceName} from {$appointment->appointedDate} to {$appointment->releasedDate}",
                    ];
                })->pluck('formattedAppointment', 'id')->toArray();


                $positions = UserServiceAppointmentPosition::join('positions', 'user_service_appointment_positions.positionId', '=', 'positions.id')
                    ->whereIn('user_service_appointment_positions.userServiceAppointmentId', array_merge(
                        $currentAppointmentIds,
                        $previousAppointmentIds,
                        $currentAttachAppointmentIds,
                        $previousAttachAppointmentIds
                    ))
                    ->select(
                        'user_service_appointment_positions.*',
                        'positions.name AS position'
                    )
                    ->get();

                // Partition positions into categories based on appointment IDs
                $positionsPartitioned = $positions->groupBy(function ($position) use (
                    $currentAppointmentIds,
                    $previousAppointmentIds,
                    $currentAttachAppointmentIds,
                    $previousAttachAppointmentIds,
                ) {
                    if (in_array($position->userServiceAppointmentId, $currentAppointmentIds)) {
                        return 'currentPositions';
                    } elseif (in_array($position->userServiceAppointmentId, $previousAppointmentIds)) {
                        return 'previousPositions';
                    } elseif (in_array($position->userServiceAppointmentId, $currentAttachAppointmentIds)) {
                        return 'currentAttachPositions';
                    } elseif (in_array($position->userServiceAppointmentId, $previousAttachAppointmentIds)) {
                        return 'previousAttachPositions';
                    }
                    return null; // Ignore other cases
                });

                // Map the partitions to structured data
                $currentPositions = $positionsPartitioned->get('currentPositions', collect())
                    ->map(function ($position) {
                        return [
                            'id' => $position->id,
                            'positionName' => $position->position,
                            'details' => $position->toArray(),
                        ];
                })->values();

                $previousPositions = $positionsPartitioned->get('previousPositions', collect())
                    ->map(function ($position) {
                        return [
                            'id' => $position->id,
                            'positionName' => $position->position,
                            'details' => $position->toArray(),
                        ];
                })->values();

                $currentAttachPositions = $positionsPartitioned->get('currentAttachPositions', collect())
                    ->map(function ($position) {
                        return [
                            'id' => $position->id,
                            'positionName' => $position->position,
                            'details' => $position->toArray(),
                        ];
                })->values();

                $previousAttachPositions = $positionsPartitioned->get('previousAttachPositions', collect())
                    ->map(function ($position) {
                        return [
                            'id' => $position->id,
                            'positionName' => $position->position,
                            'details' => $position->toArray(),
                        ];
                })->values();


                $educationQualifications = EducationQualification::join('education_qualification_infos', 'education_qualification_infos.educationQualificationId', '=', 'education_qualifications.id')
                ->where('education_qualification_infos.userId', $decryptedId)
                ->where('education_qualification_infos.active', 1)
                ->where('education_qualifications.active', 1)
                ->selectRaw("GROUP_CONCAT(CONCAT(education_qualifications.name, ' Effective from ', education_qualification_infos.effectiveDate) SEPARATOR '\n') as formattedOutput")
                ->pluck('formattedOutput')
                ->first();


                $professionalQualifications = professionalQualification::join('professional_qualification_infos', 'professional_qualification_infos.professionalQualificationId', '=', 'professional_qualifications.id')
                ->where('professional_qualification_infos.userId', $decryptedId)
                ->where('professional_qualification_infos.active', 1)
                ->where('professional_qualifications.active', 1)
                ->selectRaw("GROUP_CONCAT(CONCAT(professional_qualifications.name, ' Effective from ', professional_qualification_infos.effectiveDate) SEPARATOR '\n') as formattedOutput")
                ->pluck('formattedOutput')
                ->first();

                $family = FamilyInfo::join('family_member_types', 'family_infos.memberType', '=', 'family_member_types.id')
                ->where('family_infos.userId', $decryptedId)
                ->where('family_infos.active', 1)
                ->selectRaw("GROUP_CONCAT(CONCAT(family_infos.name, ' ( ', family_infos.nic, ' ', family_member_types.name, ' ', family_infos.profession, ' )') SEPARATOR '\n') as formattedOutput")
                ->pluck('formattedOutput')
                ->first();



                //dd($family);
                return view('teacher/profile', compact(
                    'teacher',
                    'currentServiceArray',
                    'previousServicesArray',
                    'currentServiceRanksArray',
                    'previousServiceRanksArray',
                    'currentAppointments',
                    'previousAppointments',
                    'currentAttachAppointments',
                    'previousAttachAppointments',
                    'currentPositions',
                    'previousPositions',
                    'currentAttachPositions',
                    'previousAttachPositions',
                    'educationQualifications',
                    'professionalQualifications',
                    'family',
                    'option'
                ));


            }catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
                // Redirect to the search page or show an error message for invalid ID
                return redirect()->route('teacher.search')->with('error', 'Invalid teacher ID provided.');
            }

        }else{
            return redirect()->route('teacher.search');
        }
    }

    public function principalprofile(Request $request)
    {
        if($request->has('id')){
            try{

                $option = [
                    'Dashboard' => 'dashboard',
                    'Principal Dashboard' => 'principal.dashboard',
                    'Principal Search' => 'principal.search',
                    'Principal Profile' => route('principal.profile', ['id' => $request->id]),
                ];

                $decryptedId = Crypt::decryptString($request->id);
                //dd($decryptedId);
                $principal = User::join('personal_infos', 'users.id', '=', 'personal_infos.userId')
                ->leftjoin('races', 'personal_infos.raceId', '=', 'races.id')
                ->leftjoin('religions', 'personal_infos.religionId', '=', 'religions.id')
                ->leftjoin('civil_statuses', 'personal_infos.civilStatusId', '=', 'civil_statuses.id')
                ->leftjoin('contact_infos', 'users.id', '=', 'contact_infos.userId')
                ->leftjoin('location_infos', 'users.id', '=', 'location_infos.userId')
                //->leftjoin('offices', 'location_infos.educationDivisionId', '=', 'offices.id')
                ->leftjoin('work_places AS educationDivisions', 'location_infos.educationDivisionId', '=', 'educationDivisions.id')
                ->leftjoin('gn_divisions', 'location_infos.gnDivisionId', '=', 'gn_divisions.id')
                ->leftjoin('ds_divisions', 'gn_divisions.dsId', '=', 'ds_divisions.id')
                ->leftjoin('districts', 'ds_divisions.districtId', '=', 'districts.id')
                ->leftjoin('provinces', 'districts.provinceId', '=', 'provinces.id')
                ->where('users.id', $decryptedId)
                ->select(
                    'users.id AS userId','users.name AS name','users.nic','users.email','users.nameWithInitials',
                    'personal_infos.birthDay','personal_infos.profilePicture',
                    DB::raw("CASE
                        WHEN personal_infos.genderId = 1 THEN 'Male'
                        WHEN personal_infos.genderId = 2 THEN 'Female'
                        ELSE 'Unknown'
                    END AS gender"),
                    'races.name AS race',
                    'religions.name AS religion',
                    'civil_statuses.name AS civilStatus',
                    'contact_infos.*',
                    'educationDivisions.name AS educationDivision',
                    'gn_divisions.name AS gnDivision',
                    'ds_divisions.name AS dsDivision',
                    'districts.name AS district',
                    'provinces.name AS province',
                )
                ->first();
                //dd($principal);

                $combinedData = UserInService::join('services', 'user_in_services.serviceId', '=', 'services.id')
                    ->leftJoin('user_service_in_ranks', 'user_in_services.id', '=', 'user_service_in_ranks.userServiceId')
                    ->leftJoin('ranks', 'user_service_in_ranks.rankId', '=', 'ranks.id')
                    ->where('user_in_services.userId', $decryptedId)
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
                        'ranks.name AS rank'
                    )
                    ->get();

                // Partition services into current and previous
                $partitionedData = $combinedData->partition(function ($item) {
                    return $item->currentService == 1 && is_null($item->releasedDate);
                });

                // Get distinct current services (no ranks)
                $currentService = $partitionedData[0]
                    ->unique('userServiceId')
                    ->map(function ($item) {
                        $servicePeriod = "from {$item->appointedDate} to " . ($item->releasedDate ?? 'present');
                        return [
                            'userServiceId' => $item->userServiceId,
                            'formattedService' => "{$item->serviceName} {$servicePeriod}",
                        ];
                    }); // Keep as a collection

                // Extract current service IDs
                $currentServiceIds = $currentService->pluck('userServiceId');

                // If you need to convert $currentService to an array for Blade:
                $currentServiceArray = $currentService->pluck('formattedService', 'userServiceId')->toArray();


                $previousServices = $partitionedData[1]
                ->unique('userServiceId')
                ->map(function ($item) {
                    $servicePeriod = "from {$item->appointedDate} to " . ($item->releasedDate ?? 'present');
                    return [
                        'userServiceId' => $item->userServiceId,
                        'formattedService' => "{$item->serviceName} {$servicePeriod}",
                    ];
                }); // Keep as a collection

                $previousServiceIds = $previousServices->pluck('userServiceId');

                // If you need to convert $previousServices to an array for Blade:
                $previousServicesArray = $previousServices->pluck('formattedService', 'userServiceId')->toArray();


                $currentServiceRanks = $combinedData->filter(function ($item) use ($currentServiceIds) {
                    return $currentServiceIds->contains($item->userServiceId) && !is_null($item->serviceRankId);
                })->map(function ($item) {
                    $rankPeriod = "from {$item->rankedDate}";
                    return [
                        'userServiceId' => $item->userServiceId,
                        'formattedRank' => "{$item->rank} {$rankPeriod}",
                    ];
                });

                // Convert to an array for Blade if needed
                $currentServiceRanksArray = $currentServiceRanks->pluck('formattedRank', 'userServiceId')->toArray();

                $previousServiceRanks = $combinedData->filter(function ($item) use ($previousServiceIds) {
                    return $previousServiceIds->contains($item->userServiceId) && !is_null($item->serviceRankId);
                })->map(function ($item) {
                    $rankPeriod = "from {$item->rankedDate}";
                    return [
                        'userServiceId' => $item->userServiceId,
                        'formattedRank' => "{$item->rank} {$rankPeriod}",
                    ];
                });

                // Convert to an array for Blade if needed
                $previousServiceRanksArray = $previousServiceRanks->pluck('formattedRank', 'userServiceId')->toArray();


                // Fetch appointments and categorize them into current and previous based on the service IDs
                $appointments = UserServiceAppointment::join('work_places', 'user_service_appointments.workPlaceId', '=', 'work_places.id')
                ->whereIn('user_service_appointments.userServiceId', $currentServiceIds)
                ->orWhereIn('user_service_appointments.userServiceId', $previousServiceIds)
                ->select(
                    'user_service_appointments.*',
                    'work_places.name AS workPlaceName',
                    'work_places.censusNo AS censusNo',
                    'work_places.categoryId AS workPlaceCategory'
                )
                ->get();

                // Partition appointments into categories based on their attributes
                $appointmentsPartitioned = $appointments->groupBy(function ($appointment) {
                if ($appointment->current == 1 && is_null($appointment->releasedDate)) {
                    return $appointment->appointmentType == 1 ? 'currentAppointments' : 'currentAttachAppointments';
                } elseif ($appointment->current == 0 && !is_null($appointment->releasedDate)) {
                    return $appointment->appointmentType == 1 ? 'previousAppointments' : 'previousAttachAppointments';
                }
                return null; // Ignore other cases
                });

                // Map the partitions to IDs
                $currentAppointmentIds = $appointmentsPartitioned->get('currentAppointments', collect())->pluck('id')->toArray();
                $previousAppointmentIds = $appointmentsPartitioned->get('previousAppointments', collect())->pluck('id')->toArray();
                $currentAttachAppointmentIds = $appointmentsPartitioned->get('currentAttachAppointments', collect())->pluck('id')->toArray();
                $previousAttachAppointmentIds = $appointmentsPartitioned->get('previousAttachAppointments', collect())->pluck('id')->toArray();

                // Format and return results for each category
                $currentAppointments = $appointmentsPartitioned->get('currentAppointments', collect())
                ->map(function ($appointment) {
                    return [
                        'id' => $appointment->id,
                        'formattedAppointment' => "{$appointment->workPlaceName} from {$appointment->appointedDate}",
                    ];
                })->pluck('formattedAppointment', 'id')->toArray();

                $previousAppointments = $appointmentsPartitioned->get('previousAppointments', collect())
                ->map(function ($appointment) {
                    return [
                        'id' => $appointment->id,
                        'formattedAppointment' => "{$appointment->workPlaceName} from {$appointment->appointedDate} to {$appointment->releasedDate}",
                    ];
                })->pluck('formattedAppointment', 'id')->toArray();

                $currentAttachAppointments = $appointmentsPartitioned->get('currentAttachAppointments', collect())
                ->map(function ($appointment) {
                    return [
                        'id' => $appointment->id,
                        'formattedAppointment' => "{$appointment->workPlaceName} from {$appointment->appointedDate}",
                    ];
                })->pluck('formattedAppointment', 'id')->toArray();

                $previousAttachAppointments = $appointmentsPartitioned->get('previousAttachAppointments', collect())
                ->map(function ($appointment) {
                    return [
                        'id' => $appointment->id,
                        'formattedAppointment' => "{$appointment->workPlaceName} from {$appointment->appointedDate} to {$appointment->releasedDate}",
                    ];
                })->pluck('formattedAppointment', 'id')->toArray();


                $positions = UserServiceAppointmentPosition::join('positions', 'user_service_appointment_positions.positionId', '=', 'positions.id')
                    ->whereIn('user_service_appointment_positions.userServiceAppointmentId', array_merge(
                        $currentAppointmentIds,
                        $previousAppointmentIds,
                        $currentAttachAppointmentIds,
                        $previousAttachAppointmentIds
                    ))
                    ->select(
                        'user_service_appointment_positions.*',
                        'positions.name AS position'
                    )
                    ->get();

                // Partition positions into categories based on appointment IDs
                $positionsPartitioned = $positions->groupBy(function ($position) use (
                    $currentAppointmentIds,
                    $previousAppointmentIds,
                    $currentAttachAppointmentIds,
                    $previousAttachAppointmentIds,
                ) {
                    if (in_array($position->userServiceAppointmentId, $currentAppointmentIds)) {
                        return 'currentPositions';
                    } elseif (in_array($position->userServiceAppointmentId, $previousAppointmentIds)) {
                        return 'previousPositions';
                    } elseif (in_array($position->userServiceAppointmentId, $currentAttachAppointmentIds)) {
                        return 'currentAttachPositions';
                    } elseif (in_array($position->userServiceAppointmentId, $previousAttachAppointmentIds)) {
                        return 'previousAttachPositions';
                    }
                    return null; // Ignore other cases
                });

                // Map the partitions to structured data
                $currentPositions = $positionsPartitioned->get('currentPositions', collect())
                    ->map(function ($position) {
                        return [
                            'id' => $position->id,
                            'positionName' => $position->position,
                            'details' => $position->toArray(),
                        ];
                })->values();

                $previousPositions = $positionsPartitioned->get('previousPositions', collect())
                    ->map(function ($position) {
                        return [
                            'id' => $position->id,
                            'positionName' => $position->position,
                            'details' => $position->toArray(),
                        ];
                })->values();

                $currentAttachPositions = $positionsPartitioned->get('currentAttachPositions', collect())
                    ->map(function ($position) {
                        return [
                            'id' => $position->id,
                            'positionName' => $position->position,
                            'details' => $position->toArray(),
                        ];
                })->values();

                $previousAttachPositions = $positionsPartitioned->get('previousAttachPositions', collect())
                    ->map(function ($position) {
                        return [
                            'id' => $position->id,
                            'positionName' => $position->position,
                            'details' => $position->toArray(),
                        ];
                })->values();


                $educationQualifications = EducationQualification::join('education_qualification_infos', 'education_qualification_infos.educationQualificationId', '=', 'education_qualifications.id')
                ->where('education_qualification_infos.userId', $decryptedId)
                ->where('education_qualification_infos.active', 1)
                ->where('education_qualifications.active', 1)
                ->selectRaw("GROUP_CONCAT(CONCAT(education_qualifications.name, ' Effective from ', education_qualification_infos.effectiveDate) SEPARATOR '\n') as formattedOutput")
                ->pluck('formattedOutput')
                ->first();


                $professionalQualifications = professionalQualification::join('professional_qualification_infos', 'professional_qualification_infos.professionalQualificationId', '=', 'professional_qualifications.id')
                ->where('professional_qualification_infos.userId', $decryptedId)
                ->where('professional_qualification_infos.active', 1)
                ->where('professional_qualifications.active', 1)
                ->selectRaw("GROUP_CONCAT(CONCAT(professional_qualifications.name, ' Effective from ', professional_qualification_infos.effectiveDate) SEPARATOR '\n') as formattedOutput")
                ->pluck('formattedOutput')
                ->first();

                $family = FamilyInfo::join('family_member_types', 'family_infos.memberType', '=', 'family_member_types.id')
                ->where('family_infos.userId', $decryptedId)
                ->where('family_infos.active', 1)
                ->selectRaw("GROUP_CONCAT(CONCAT(family_infos.name, ' ( ', family_infos.nic, ' ', family_member_types.name, ' ', family_infos.profession, ' )') SEPARATOR '\n') as formattedOutput")
                ->pluck('formattedOutput')
                ->first();



                //dd($family);
                return view('principal/profile', compact(
                    'principal',
                    'currentServiceArray',
                    'previousServicesArray',
                    'currentServiceRanksArray',
                    'previousServiceRanksArray',
                    'currentAppointments',
                    'previousAppointments',
                    'currentAttachAppointments',
                    'previousAttachAppointments',
                    'currentPositions',
                    'previousPositions',
                    'currentAttachPositions',
                    'previousAttachPositions',
                    'educationQualifications',
                    'professionalQualifications',
                    'family',
                    'option'
                ));


            }catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
                // Redirect to the search page or show an error message for invalid ID
                return redirect()->route('principal.search')->with('error', 'Invalid principal ID provided.');
            }

        }else{
            return redirect()->route('principal.search');
        }
    }

    public function sleasprofile(Request $request)
    {
        if($request->has('id')){
            try{

                $option = [
                    'Dashboard' => 'dashboard',
                    'SLEAS Dashboard' => 'sleas.dashboard',
                    'SLEAS Search' => 'sleas.search',
                    'SLEAS Profile' => route('sleas.profile', ['id' => $request->id]),
                ];

                $decryptedId = Crypt::decryptString($request->id);
                //dd($decryptedId);
                $sleas = User::join('personal_infos', 'users.id', '=', 'personal_infos.userId')
                ->leftjoin('races', 'personal_infos.raceId', '=', 'races.id')
                ->leftjoin('religions', 'personal_infos.religionId', '=', 'religions.id')
                ->leftjoin('civil_statuses', 'personal_infos.civilStatusId', '=', 'civil_statuses.id')
                ->leftjoin('contact_infos', 'users.id', '=', 'contact_infos.userId')
                ->leftjoin('location_infos', 'users.id', '=', 'location_infos.userId')
                //->leftjoin('offices', 'location_infos.educationDivisionId', '=', 'offices.id')
                ->leftjoin('work_places AS educationDivisions', 'location_infos.educationDivisionId', '=', 'educationDivisions.id')
                ->leftjoin('gn_divisions', 'location_infos.gnDivisionId', '=', 'gn_divisions.id')
                ->leftjoin('ds_divisions', 'gn_divisions.dsId', '=', 'ds_divisions.id')
                ->leftjoin('districts', 'ds_divisions.districtId', '=', 'districts.id')
                ->leftjoin('provinces', 'districts.provinceId', '=', 'provinces.id')
                ->where('users.id', $decryptedId)
                ->select(
                    'users.id AS userId','users.name AS name','users.nic','users.email','users.nameWithInitials',
                    'personal_infos.birthDay','personal_infos.profilePicture',
                    DB::raw("CASE
                        WHEN personal_infos.genderId = 1 THEN 'Male'
                        WHEN personal_infos.genderId = 2 THEN 'Female'
                        ELSE 'Unknown'
                    END AS gender"),
                    'races.name AS race',
                    'religions.name AS religion',
                    'civil_statuses.name AS civilStatus',
                    'contact_infos.*',
                    'educationDivisions.name AS educationDivision',
                    'gn_divisions.name AS gnDivision',
                    'ds_divisions.name AS dsDivision',
                    'districts.name AS district',
                    'provinces.name AS province',
                )
                ->first();
                //dd($teacher);

                $combinedData = UserInService::join('services', 'user_in_services.serviceId', '=', 'services.id')
                    ->leftJoin('user_service_in_ranks', 'user_in_services.id', '=', 'user_service_in_ranks.userServiceId')
                    ->leftJoin('ranks', 'user_service_in_ranks.rankId', '=', 'ranks.id')
                    ->where('user_in_services.userId', $decryptedId)
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
                        'ranks.name AS rank'
                    )
                    ->get();

                // Partition services into current and previous
                $partitionedData = $combinedData->partition(function ($item) {
                    return $item->currentService == 1 && is_null($item->releasedDate);
                });

                // Get distinct current services (no ranks)
                $currentService = $partitionedData[0]
                    ->unique('userServiceId')
                    ->map(function ($item) {
                        $servicePeriod = "from {$item->appointedDate} to " . ($item->releasedDate ?? 'present');
                        return [
                            'userServiceId' => $item->userServiceId,
                            'formattedService' => "{$item->serviceName} {$servicePeriod}",
                        ];
                    }); // Keep as a collection

                // Extract current service IDs
                $currentServiceIds = $currentService->pluck('userServiceId');

                // If you need to convert $currentService to an array for Blade:
                $currentServiceArray = $currentService->pluck('formattedService', 'userServiceId')->toArray();


                $previousServices = $partitionedData[1]
                ->unique('userServiceId')
                ->map(function ($item) {
                    $servicePeriod = "from {$item->appointedDate} to " . ($item->releasedDate ?? 'present');
                    return [
                        'userServiceId' => $item->userServiceId,
                        'formattedService' => "{$item->serviceName} {$servicePeriod}",
                    ];
                }); // Keep as a collection

                $previousServiceIds = $previousServices->pluck('userServiceId');

                // If you need to convert $previousServices to an array for Blade:
                $previousServicesArray = $previousServices->pluck('formattedService', 'userServiceId')->toArray();


                $currentServiceRanks = $combinedData->filter(function ($item) use ($currentServiceIds) {
                    return $currentServiceIds->contains($item->userServiceId) && !is_null($item->serviceRankId);
                })->map(function ($item) {
                    $rankPeriod = "from {$item->rankedDate}";
                    return [
                        'userServiceId' => $item->userServiceId,
                        'formattedRank' => "{$item->rank} {$rankPeriod}",
                    ];
                });

                // Convert to an array for Blade if needed
                $currentServiceRanksArray = $currentServiceRanks->pluck('formattedRank', 'userServiceId')->toArray();

                $previousServiceRanks = $combinedData->filter(function ($item) use ($previousServiceIds) {
                    return $previousServiceIds->contains($item->userServiceId) && !is_null($item->serviceRankId);
                })->map(function ($item) {
                    $rankPeriod = "from {$item->rankedDate}";
                    return [
                        'userServiceId' => $item->userServiceId,
                        'formattedRank' => "{$item->rank} {$rankPeriod}",
                    ];
                });

                // Convert to an array for Blade if needed
                $previousServiceRanksArray = $previousServiceRanks->pluck('formattedRank', 'userServiceId')->toArray();


                // Fetch appointments and categorize them into current and previous based on the service IDs
                $appointments = UserServiceAppointment::join('work_places', 'user_service_appointments.workPlaceId', '=', 'work_places.id')
                ->whereIn('user_service_appointments.userServiceId', $currentServiceIds)
                ->orWhereIn('user_service_appointments.userServiceId', $previousServiceIds)
                ->select(
                    'user_service_appointments.*',
                    'work_places.name AS workPlaceName',
                    'work_places.censusNo AS censusNo',
                    'work_places.categoryId AS workPlaceCategory'
                )
                ->get();

                // Partition appointments into categories based on their attributes
                $appointmentsPartitioned = $appointments->groupBy(function ($appointment) {
                if ($appointment->current == 1 && is_null($appointment->releasedDate)) {
                    return $appointment->appointmentType == 1 ? 'currentAppointments' : 'currentAttachAppointments';
                } elseif ($appointment->current == 0 && !is_null($appointment->releasedDate)) {
                    return $appointment->appointmentType == 1 ? 'previousAppointments' : 'previousAttachAppointments';
                }
                return null; // Ignore other cases
                });

                // Map the partitions to IDs
                $currentAppointmentIds = $appointmentsPartitioned->get('currentAppointments', collect())->pluck('id')->toArray();
                $previousAppointmentIds = $appointmentsPartitioned->get('previousAppointments', collect())->pluck('id')->toArray();
                $currentAttachAppointmentIds = $appointmentsPartitioned->get('currentAttachAppointments', collect())->pluck('id')->toArray();
                $previousAttachAppointmentIds = $appointmentsPartitioned->get('previousAttachAppointments', collect())->pluck('id')->toArray();

                // Format and return results for each category
                $currentAppointments = $appointmentsPartitioned->get('currentAppointments', collect())
                ->map(function ($appointment) {
                    return [
                        'id' => $appointment->id,
                        'formattedAppointment' => "{$appointment->workPlaceName} from {$appointment->appointedDate}",
                    ];
                })->pluck('formattedAppointment', 'id')->toArray();

                $previousAppointments = $appointmentsPartitioned->get('previousAppointments', collect())
                ->map(function ($appointment) {
                    return [
                        'id' => $appointment->id,
                        'formattedAppointment' => "{$appointment->workPlaceName} from {$appointment->appointedDate} to {$appointment->releasedDate}",
                    ];
                })->pluck('formattedAppointment', 'id')->toArray();

                $currentAttachAppointments = $appointmentsPartitioned->get('currentAttachAppointments', collect())
                ->map(function ($appointment) {
                    return [
                        'id' => $appointment->id,
                        'formattedAppointment' => "{$appointment->workPlaceName} from {$appointment->appointedDate}",
                    ];
                })->pluck('formattedAppointment', 'id')->toArray();

                $previousAttachAppointments = $appointmentsPartitioned->get('previousAttachAppointments', collect())
                ->map(function ($appointment) {
                    return [
                        'id' => $appointment->id,
                        'formattedAppointment' => "{$appointment->workPlaceName} from {$appointment->appointedDate} to {$appointment->releasedDate}",
                    ];
                })->pluck('formattedAppointment', 'id')->toArray();


                $positions = UserServiceAppointmentPosition::join('positions', 'user_service_appointment_positions.positionId', '=', 'positions.id')
                    ->whereIn('user_service_appointment_positions.userServiceAppointmentId', array_merge(
                        $currentAppointmentIds,
                        $previousAppointmentIds,
                        $currentAttachAppointmentIds,
                        $previousAttachAppointmentIds
                    ))
                    ->select(
                        'user_service_appointment_positions.*',
                        'positions.name AS position'
                    )
                    ->get();

                // Partition positions into categories based on appointment IDs
                $positionsPartitioned = $positions->groupBy(function ($position) use (
                    $currentAppointmentIds,
                    $previousAppointmentIds,
                    $currentAttachAppointmentIds,
                    $previousAttachAppointmentIds,
                ) {
                    if (in_array($position->userServiceAppointmentId, $currentAppointmentIds)) {
                        return 'currentPositions';
                    } elseif (in_array($position->userServiceAppointmentId, $previousAppointmentIds)) {
                        return 'previousPositions';
                    } elseif (in_array($position->userServiceAppointmentId, $currentAttachAppointmentIds)) {
                        return 'currentAttachPositions';
                    } elseif (in_array($position->userServiceAppointmentId, $previousAttachAppointmentIds)) {
                        return 'previousAttachPositions';
                    }
                    return null; // Ignore other cases
                });

                // Map the partitions to structured data
                $currentPositions = $positionsPartitioned->get('currentPositions', collect())
                    ->map(function ($position) {
                        return [
                            'id' => $position->id,
                            'positionName' => $position->position,
                            'details' => $position->toArray(),
                        ];
                })->values();

                $previousPositions = $positionsPartitioned->get('previousPositions', collect())
                    ->map(function ($position) {
                        return [
                            'id' => $position->id,
                            'positionName' => $position->position,
                            'details' => $position->toArray(),
                        ];
                })->values();

                $currentAttachPositions = $positionsPartitioned->get('currentAttachPositions', collect())
                    ->map(function ($position) {
                        return [
                            'id' => $position->id,
                            'positionName' => $position->position,
                            'details' => $position->toArray(),
                        ];
                })->values();

                $previousAttachPositions = $positionsPartitioned->get('previousAttachPositions', collect())
                    ->map(function ($position) {
                        return [
                            'id' => $position->id,
                            'positionName' => $position->position,
                            'details' => $position->toArray(),
                        ];
                })->values();


                $educationQualifications = EducationQualification::join('education_qualification_infos', 'education_qualification_infos.educationQualificationId', '=', 'education_qualifications.id')
                ->where('education_qualification_infos.userId', $decryptedId)
                ->where('education_qualification_infos.active', 1)
                ->where('education_qualifications.active', 1)
                ->selectRaw("GROUP_CONCAT(CONCAT(education_qualifications.name, ' Effective from ', education_qualification_infos.effectiveDate) SEPARATOR '\n') as formattedOutput")
                ->pluck('formattedOutput')
                ->first();


                $professionalQualifications = professionalQualification::join('professional_qualification_infos', 'professional_qualification_infos.professionalQualificationId', '=', 'professional_qualifications.id')
                ->where('professional_qualification_infos.userId', $decryptedId)
                ->where('professional_qualification_infos.active', 1)
                ->where('professional_qualifications.active', 1)
                ->selectRaw("GROUP_CONCAT(CONCAT(professional_qualifications.name, ' Effective from ', professional_qualification_infos.effectiveDate) SEPARATOR '\n') as formattedOutput")
                ->pluck('formattedOutput')
                ->first();

                $family = FamilyInfo::join('family_member_types', 'family_infos.memberType', '=', 'family_member_types.id')
                ->where('family_infos.userId', $decryptedId)
                ->where('family_infos.active', 1)
                ->selectRaw("GROUP_CONCAT(CONCAT(family_infos.name, ' ( ', family_infos.nic, ' ', family_member_types.name, ' ', family_infos.profession, ' )') SEPARATOR '\n') as formattedOutput")
                ->pluck('formattedOutput')
                ->first();



                //dd($family);
                return view('sleas/profile', compact(
                    'sleas',
                    'currentServiceArray',
                    'previousServicesArray',
                    'currentServiceRanksArray',
                    'previousServiceRanksArray',
                    'currentAppointments',
                    'previousAppointments',
                    'currentAttachAppointments',
                    'previousAttachAppointments',
                    'currentPositions',
                    'previousPositions',
                    'currentAttachPositions',
                    'previousAttachPositions',
                    'educationQualifications',
                    'professionalQualifications',
                    'family',
                    'option'
                ));


            }catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
                // Redirect to the search page or show an error message for invalid ID
                return redirect()->route('sleas.search')->with('error', 'Invalid SLEAS ID provided.');
            }

        }else{
            return redirect()->route('sleas.search');
        }
    }

    public function teacherreports()
    {
        $option = [
            'Dashboard' => 'dashboard',
            'Teacher Dashboard' => 'teacher.dashboard',
            'Teacher Reports' => 'teacher.reports'
        ];
        return view('teacher/reports',compact('option'));
    }

    public function principalreports()
    {
        $option = [
            'Dashboard' => 'dashboard',
            'Principal Dashboard' => 'principal.dashboard',
            'Principal Reports' => 'principal.reports'
        ];
        return view('principal/reports',compact('option'));
    }

    public function sleasreports()
    {
        $option = [
            'Dashboard' => 'dashboard',
            'SLEAS Dashboard' => 'sleas.dashboard',
            'SLEAS Reports' => 'sleas.reports'
        ];
        return view('sleas/reports',compact('option'));
    }

    public function teacherfullreportcurrent()
    {
        $option = [
            'Dashboard' => 'dashboard',
            'Teacher Dashboard' => 'teacher.dashboard',
            'Teacher Reports' => 'teacher.reports',
            'Teacher Full Report' => 'teacher.fullreportcurrent',
        ];
        return view('teacher/fullreport',compact('option'));
    }

    public function principalfullreportcurrent()
    {
        $option = [
            'Dashboard' => 'dashboard',
            'Principal Dashboard' => 'principal.dashboard',
            'Principal Reports' => 'principal.reports',
            'Principal Full Report' => 'principal.fullreportcurrent',
        ];
        return view('principal/fullreport',compact('option'));
    }

    public function sleasfullreportcurrent()
    {
        $option = [
            'Dashboard' => 'dashboard',
            'SLEAS Dashboard' => 'sleas.dashboard',
            'SLEAS Reports' => 'sleas.reports',
            'SLEAS Full Report' => 'sleas.fullreportcurrent',
        ];
        return view('sleas/fullreport',compact('option'));
    }


    public function teacherprofileedit(Request $request)
    {
        if($request->has('id') && $request->has('category')){
            try{

                $option = [
                    'Dashboard' => 'dashboard',
                    'Teacher Dashboard' => 'teacher.dashboard',
                    'Teacher Search' => 'teacher.search',
                    'Teacher Profile' => route('teacher.profile', ['id' => $request->id]),
                    'Teacher Profile Edit' => htmlspecialchars_decode(route('teacher.profileedit',['id' => $request->id,'category' => $request->category])),
                ];

                $decryptedId = Crypt::decryptString($request->id);
                $category = $request->category;

                $teacher = User::find($decryptedId);

                $races = collect([]);
                $religions = collect([]);
                $civilStatuses = collect([]);
                if($category == 'personal')
                {
                    $races = Race::where('active', 1)->get();
                    $religions = Religion::where('active', 1)->get();
                    $civilStatuses = CivilStatus::where('active', 1)->get();
                }


                $services = collect([]);
                $current_services = collect([]);
                if($category == 'service')
                {
                    $services = Service::where('active', 1)->get();
                    $current_services = DB::table('user_in_services')
                        ->join('services', 'user_in_services.serviceId', '=', 'services.id')
                        ->where('user_in_services.id', $teacher->id)
                        ->select('user_in_services.id AS id', 'services.name AS name') // adjust fields as needed
                        ->get();

                    //dd($user_services);
                }

                $appointment_lists = collect([]);
                if($category == 'appointment')
                {
                    $appointment_lists = DB::table('user_in_services')
                    ->join('services', 'user_in_services.serviceId', '=', 'services.id')
                    ->join('user_service_appointments', 'user_service_appointments.userServiceId', '=', 'user_in_services.id')
                    ->join('work_places', 'work_places.id', '=', 'user_service_appointments.workPlaceId')
                    ->where('user_in_services.userId', $teacher->id)
                    ->where('user_in_services.active', 1)
                    ->where('services.active', 1)
                    ->where('user_service_appointments.active', 1)
                    ->where('work_places.active', 1)
                    ->whereNotNull('user_service_appointments.releasedDate')
                    ->select(
                        'user_service_appointments.id as id',
                        DB::raw("CONCAT(services.name, ' | ', work_places.name, ' | ', user_service_appointments.appointedDate, ' - ', user_service_appointments.releasedDate) as name")
                    )
                    ->get();

                }

                $educationQualifications = collect([]);
                $professionalQualifications = collect([]);
                if($category == 'qualification')
                {
                    $educationQualifications = EducationQualification::where('active', 1)->get();
                    $professionalQualifications = ProfessionalQualification::where('active', 1)->get();
                }

                $ranks = collect([]);
                if($category == 'rank')
                {
                    $ranks = Rank::where('active', 1)
                    ->where('serviceId', 1)
                    ->get();

                }

                return view('teacher/profile-edit', compact(
                    'teacher',
                    'category',
                    'races',
                    'religions',
                    'civilStatuses',
                    'services',
                    'current_services',
                    'appointment_lists',
                    'educationQualifications',
                    'professionalQualifications',
                    'ranks',
                    'option'
                ));


            }catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
                // Redirect to the search page or show an error message for invalid ID
                return redirect()->route('teacher.search')->with('error', 'Invalid teacher ID provided.');
            }

        }else{
            return redirect()->route('teacher.search');
        }
    }


    // public function index()
    // {
    //     return response()->json(User::all(), 200);
    // }

    // public function show($id)
    // {
    //     $user = User::find($id);
    //     return $user ? response()->json($user, 200) : response()->json(['error' => 'User not found'], 404);
    // }

    public function index()
    {
        return UserResource::collection(User::all()); // Returns formatted user list
    }

    public function show($id)
    {
        return new UserResource(User::findOrFail($id)); // Returns single user data
    }

    public function store(Request $request)
    {
        $user = User::create($request->all());
        return response()->json($user, 201);
    }

    public function update(Request $request, $id)
    {
        $user = User::find($id);
        if (!$user) return response()->json(['error' => 'User not found'], 404);

        $user->update($request->all());
        return response()->json($user, 200);
    }

    public function destroy($id)
    {
        $user = User::find($id);
        if (!$user) return response()->json(['error' => 'User not found'], 404);

        $user->delete();
        return response()->json(['message' => 'User deleted'], 200);
    }
}
