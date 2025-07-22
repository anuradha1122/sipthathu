<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreteacherTransferRequest;
use App\Http\Requests\UpdateteacherTransferRequest;
use App\Models\teacherTransfer;
use App\Models\UserInService;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class TeacherTransferController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //dd(session('appointmentId'));
        $userInService = UserInService::where('id', session('userServiceId'))->first();


        // Assuming $userServiceAppointment->appointedDate is a valid date (e.g., '2020-01-15')
        $appointedDate = Carbon::parse($userInService->appointedDate);
        $comparisonDate = Carbon::parse('2025-07-01');
        $years = $appointedDate->diffInYears($comparisonDate);


        $option = [
            'Dashboard' => 'teacher.transferdashboard',
        ];
        return view('teacher/transfer-dashboard',compact('option','years'));
    }

    public function teacherindex()
    {
        //dd(session('higherZoneId'));
        // $transferTypes = TransferType::where('active', 1)->get();
        // $transferReasons = TransferReason::where('active', 1)->get();
        $binaryList = collect([
            (object)['id' => 1, 'name' => 'yes'],
            (object)['id' => 2, 'name' => 'no'],
        ]);

        $teachingGradeList = collect([
            (object)['id' => 1, 'name' => '1-2'],
            (object)['id' => 2, 'name' => '3-4'],
            (object)['id' => 3, 'name' => '5'],
        ]);

        $zoneSchools = DB::table('work_places')
            ->join('schools', 'work_places.id', '=', 'schools.workPlaceId')
            ->join('offices', 'schools.officeId', '=', 'offices.id')
            ->where('offices.higherOfficeId', session('higherZoneId'))
            ->where('schools.authorityId', 2)
            ->select('work_places.name', 'schools.id') // or choose specific columns
            ->get();

        //dd($zoneSchools);
        // $appointmentCategories = AppointmentCategory::where('active', 1)->get();
        // $ranks = Rank::where('active', 1)
        //     ->where('serviceId', 1) // Filter by serviceId
        //     ->get();


        $option = [
            'Dashboard' => 'teacher.transferdashboard',
            'Transfer Form' => 'teacher.transfer',
        ];
        return view('teacher/transferform',compact('option','binaryList','teachingGradeList','zoneSchools'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreteacherTransferRequest $request)
    {
        //
    }

    public function teacherstore(StoreteacherTransferRequest $request)
    {
        //dd(session()->all());

        $userServiceId = session('userServiceId');
        //dd($userServiceId);
        TeacherTransfer::where('userServiceId', $userServiceId)
            ->where('typeId', $request->type)
            ->where('active', 1)
            ->update(['active' => 0]);

        $referenceNo = now()->format('Ymd') . str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        TeacherTransfer::create([
            'refferenceNo'       => $referenceNo,
            'userServiceId'      => $userServiceId,
            'typeId'             => $request->type,
            'reasonId'           => $request->reason,
            'school1Id'          => $request->school1,
            'school2Id'          => $request->school2,
            'school3Id'          => $request->school3,
            'school4Id'          => $request->school4,
            'school5Id'          => $request->school5,
            'alterSchool1Id'     => $request->alterSchool1,
            'alterSchool2Id'     => $request->alterSchool2,
            'alterSchool3Id'     => $request->alterSchool3,
            'alterSchool4Id'     => $request->alterSchool4,
            'alterSchool5Id'     => $request->alterSchool5,
            'anySchool'          => $request->alternativeSchool,
            'gradeId'            => $request->teachingGrades,
            'extraCurricular'    => $request->extraCurricular,
            'mention'            => $request->mention,
            'active'             => 1,
            'anyschool'          => $request->alternativeSchool,
        ]);


        $typeId = $request->type;
        $option = [
            'Transfer Form' => 'teacher.transfer',
        ];

        return view('teacher/after-transfer-form',compact('option', 'typeId'));
        //dd($request->all());
    }

    public function teacherPersonalPdf(Request $request)
    {
        $typeId = $request->query('typeId'); // or $request->get('typeId');

        $userServiceId = session('userServiceId'); // get from session
        $typeId = $typeId; // passed from controller or request

        $transfer = DB::table('teacher_transfers')
            // joins same as before ...
            ->leftJoin('schools as s1', 'teacher_transfers.school1Id', '=', 's1.id')
            ->leftJoin('work_places as wp1', 's1.workPlaceId', '=', 'wp1.id')
            ->leftJoin('schools as s2', 'teacher_transfers.school2Id', '=', 's2.id')
            ->leftJoin('work_places as wp2', 's2.workPlaceId', '=', 'wp2.id')
            ->leftJoin('schools as s3', 'teacher_transfers.school3Id', '=', 's3.id')
            ->leftJoin('work_places as wp3', 's3.workPlaceId', '=', 'wp3.id')
            ->leftJoin('schools as s4', 'teacher_transfers.school4Id', '=', 's4.id')
            ->leftJoin('work_places as wp4', 's4.workPlaceId', '=', 'wp4.id')
            ->leftJoin('schools as s5', 'teacher_transfers.school5Id', '=', 's5.id')
            ->leftJoin('work_places as wp5', 's5.workPlaceId', '=', 'wp5.id')

            ->leftJoin('schools as as1', 'teacher_transfers.alterSchool1Id', '=', 'as1.id')
            ->leftJoin('work_places as awp1', 'as1.workPlaceId', '=', 'awp1.id')
            ->leftJoin('schools as as2', 'teacher_transfers.alterSchool2Id', '=', 'as2.id')
            ->leftJoin('work_places as awp2', 'as2.workPlaceId', '=', 'awp2.id')
            ->leftJoin('schools as as3', 'teacher_transfers.alterSchool3Id', '=', 'as3.id')
            ->leftJoin('work_places as awp3', 'as3.workPlaceId', '=', 'awp3.id')
            ->leftJoin('schools as as4', 'teacher_transfers.alterSchool4Id', '=', 'as4.id')
            ->leftJoin('work_places as awp4', 'as4.workPlaceId', '=', 'awp4.id')
            ->leftJoin('schools as as5', 'teacher_transfers.alterSchool5Id', '=', 'as5.id')
            ->leftJoin('work_places as awp5', 'as5.workPlaceId', '=', 'awp5.id')

            ->leftJoin('transfer_types', 'teacher_transfers.typeId', '=', 'transfer_types.id')
            ->leftJoin('transfer_reasons', 'teacher_transfers.reasonId', '=', 'transfer_reasons.id')

            ->select(
                'teacher_transfers.*',
                'wp1.name as school1Name',
                'wp2.name as school2Name',
                'wp3.name as school3Name',
                'wp4.name as school4Name',
                'wp5.name as school5Name',
                'awp1.name as alterSchool1Name',
                'awp2.name as alterSchool2Name',
                'awp3.name as alterSchool3Name',
                'awp4.name as alterSchool4Name',
                'awp5.name as alterSchool5Name',
                'transfer_types.name as typeName',
                'transfer_reasons.name as reasonName',
                DB::raw("CASE teacher_transfers.anySchool WHEN 1 THEN 'Yes' WHEN 2 THEN 'No' ELSE 'N/A' END as anySchoolText"),
                DB::raw("CASE teacher_transfers.gradeId
                            WHEN 1 THEN 'Grade 1-2'
                            WHEN 2 THEN 'Grade 3-4'
                            WHEN 3 THEN 'Grade 5'
                            ELSE 'N/A' END as gradeName"),
                DB::raw("DATE_FORMAT(teacher_transfers.created_at, '%Y-%m-%d') as createdDate")
            )

            ->where('teacher_transfers.userServiceId', $userServiceId)
            ->where('teacher_transfers.typeId', $typeId)
            ->where('teacher_transfers.active', 1)

            ->first(); // or ->get() if you want multiple rows


        $pdf = Pdf::loadView('teacher.pdf.transfer-personal-pdf', compact('transfer'));

        return $pdf->download('teacher-transfer.pdf');
    }

    /**
     * Display the specified resource.
     */
    public function show(teacherTransfer $teacherTransfer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(teacherTransfer $teacherTransfer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateteacherTransferRequest $request, teacherTransfer $teacherTransfer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(teacherTransfer $teacherTransfer)
    {
        //
    }
}
