<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StorePrincipalTransferRequest;
use App\Http\Requests\UpdatePrincipalTransferRequest;
use App\Models\PrincipalTransfer;
use App\Models\UserServiceAppointment;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PrincipalTransferController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //dd(session('appointmentId'));
        $userServiceAppointment = UserServiceAppointment::where('id', session('appointmentId'))->first();


        // Assuming $userServiceAppointment->appointedDate is a valid date (e.g., '2020-01-15')
        $appointedDate = Carbon::parse($userServiceAppointment->appointedDate);
        $comparisonDate = Carbon::parse('2025-07-01');
        $years = $appointedDate->diffInYears($comparisonDate);


        $option = [
            'Dashboard' => 'principal.transferdashboard',
        ];
        return view('principal/transfer-dashboard',compact('option','years'));
    }

    public function principalindex()
    {
        //dd(session('higherZoneId'));
        // $transferTypes = TransferType::where('active', 1)->get();
        // $transferReasons = TransferReason::where('active', 1)->get();
        $binaryList = collect([
            (object)['id' => 1, 'name' => 'yes'],
            (object)['id' => 2, 'name' => 'no'],
        ]);

        $positionList = collect([
            (object)['id' => 1, 'name' => 'Principal'],
            (object)['id' => 2, 'name' => 'Vice Principal'],
            (object)['id' => 3, 'name' => 'Assistant Principal'],
        ]);

        $zoneSchools = DB::table('work_places')
            ->join('schools', 'work_places.id', '=', 'schools.workPlaceId')
            ->join('offices', 'schools.officeId', '=', 'offices.id')
            ->where('offices.higherOfficeId', session('higherZoneId'))
            ->select('work_places.name', 'schools.id') // or choose specific columns
            ->get();

        //dd($zoneSchools);
        // $appointmentCategories = AppointmentCategory::where('active', 1)->get();
        // $ranks = Rank::where('active', 1)
        //     ->where('serviceId', 1) // Filter by serviceId
        //     ->get();


        $option = [
            'Dashboard' => 'principal.transferdashboard',
            'Transfer Form' => 'principal.transfer',
        ];
        return view('principal/transferform',compact('option','positionList','binaryList','zoneSchools'));
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
    public function store(StorePrincipalTransferRequest $request)
    {
        //
    }

    public function principalstore(StorePrincipalTransferRequest $request)
    {
        //dd(session()->all());

        $userServiceId = session('userServiceId');
        //dd($request->all());
        PrincipalTransfer::where('userServiceId', $userServiceId)
            ->where('active', 1)
            ->update(['active' => 0]);

        $referenceNo = now()->format('Ymd') . str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        PrincipalTransfer::create([
            'refferenceNo'       => $referenceNo,
            'userServiceId'      => $userServiceId,
            'appointmentLetterNo' => $request->appointmentLetterNo,
            'serviceConfirm'     => $request->serviceConfirm,
            'schoolDistance'     => $request->schoolDistance,
            'position'           => $request->position,
            'specialChildren'    => $request->specialChildren,
            'expectTransfer'     => $request->expectTransfer,
            'reason'           => $request->reason,
            'school1Id'          => $request->school1,
            'distance1'          => $request->distance1,
            'school2Id'          => $request->school2,
            'distance2'          => $request->distance2,
            'school3Id'          => $request->school3,
            'distance3'          => $request->distance3,
            'school4Id'          => $request->school4,
            'distance4'          => $request->distance4,
            'school5Id'          => $request->school5,
            'distance5'          => $request->distance5,
            'anySchool'          => $request->otherSchool,
            'mention'            => $request->mention,
            'active'             => 1,
        ]);


        $typeId = $request->type;
        $option = [
            'Transfer Form' => 'principal.transfer',
        ];

        return view('principal/after-transfer-form',compact('option'));
        //dd($request->all());
    }

    public function principalPersonalPdf(Request $request)
    {
        $typeId = $request->query('typeId'); // or $request->get('typeId');

        $userServiceId = session('userServiceId'); // get from session
        $typeId = $typeId; // passed from controller or request

        $transfer = DB::table('principal_transfers')
            // joins same as before ...
            ->leftJoin('schools as s1', 'principal_transfers.school1Id', '=', 's1.id')
            ->leftJoin('work_places as wp1', 's1.workPlaceId', '=', 'wp1.id')
            ->leftJoin('schools as s2', 'principal_transfers.school2Id', '=', 's2.id')
            ->leftJoin('work_places as wp2', 's2.workPlaceId', '=', 'wp2.id')
            ->leftJoin('schools as s3', 'principal_transfers.school3Id', '=', 's3.id')
            ->leftJoin('work_places as wp3', 's3.workPlaceId', '=', 'wp3.id')
            ->leftJoin('schools as s4', 'principal_transfers.school4Id', '=', 's4.id')
            ->leftJoin('work_places as wp4', 's4.workPlaceId', '=', 'wp4.id')
            ->leftJoin('schools as s5', 'principal_transfers.school5Id', '=', 's5.id')
            ->leftJoin('work_places as wp5', 's5.workPlaceId', '=', 'wp5.id')
            ->select(
                'principal_transfers.*',
                'wp1.name as school1Name',
                'wp2.name as school2Name',
                'wp3.name as school3Name',
                'wp4.name as school4Name',
                'wp5.name as school5Name',

                DB::raw("CASE principal_transfers.anySchool WHEN 1 THEN 'Yes' WHEN 2 THEN 'No' ELSE 'N/A' END as anySchool"),
                DB::raw("CASE principal_transfers.serviceConfirm WHEN 1 THEN 'Yes' WHEN 2 THEN 'No' ELSE 'N/A' END as serviceConfirm"),
                DB::raw("CASE principal_transfers.specialChildren WHEN 1 THEN 'Yes' WHEN 2 THEN 'No' ELSE 'N/A' END as specialChildren"),
                DB::raw("CASE principal_transfers.expectTransfer WHEN 1 THEN 'Yes' WHEN 2 THEN 'No' ELSE 'N/A' END as expectTransfer"),
                DB::raw("CASE principal_transfers.position
                            WHEN 1 THEN 'Principal'
                            WHEN 2 THEN 'Vice Principal'
                            WHEN 3 THEN 'Assistant Principal'
                            ELSE 'N/A' END as position"),
                DB::raw("DATE_FORMAT(principal_transfers.created_at, '%Y-%m-%d') as createdDate")
            )

            ->where('principal_transfers.userServiceId', $userServiceId)
            ->where('principal_transfers.active', 1)

            ->first(); // or ->get() if you want multiple rows


        $pdf = Pdf::loadView('principal.pdf.transfer-personal-pdf', compact('transfer'));

        return $pdf->download('principal-transfer.pdf');
    }

    /**
     * Display the specified resource.
     */
    public function show(PrincipalTransfer $principalTransfer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PrincipalTransfer $principalTransfer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePrincipalTransferRequest $request, PrincipalTransfer $principalTransfer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PrincipalTransfer $principalTransfer)
    {
        //
    }
}
