<?php

namespace App\Http\Controllers\Leadman;

use App\Http\Controllers\Controller;
use App\Repositories\HR\EmployeeRepository;
use App\Repositories\Leadman\AttendanceRepository;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    private $attendanceRepository;
    private $employeeRepository;
    public function __construct(AttendanceRepository $attendanceRepository, EmployeeRepository $employeeRepository) {

        $this->attendanceRepository = $attendanceRepository;
        $this->employeeRepository = $employeeRepository;

    }

    public function index() {

        return view('leadman.attendance.index');

    }

    public function overtime() {
        return view('HR.overtime.index');
    }

    public function getAttendance(Request $request) {

        $page = $request->page ? $request->page : 1;
        $count = $request->count ? $request->count : 10;
        $search = $request->search && $request->search != '' && $request->search !== 'null' ? $request->search : null;
        $date = $request->date ? $request->date : null;

        $params = [
            'page' => $page,
            'count' => $count,
            'search' => $search,
            'date' => $date,
        ];

        $attendance =$this->attendanceRepository->getAttendance(json_decode(json_encode($params)));


        return response()->json($attendance, 200);

    }

    public function storeAttendance(Request $request) {

        $attendance = $this->attendanceRepository->storeAttendance(json_decode(json_encode($request->all())));

        return response()->json($attendance, 200);

    }

    public function updateAttendance($id, Request $request) {

        $attendance = $this->attendanceRepository->updateAttendance($id, json_decode(json_encode($request->all())));

        return response()->json($attendance, 200);

    }

    public function timeIn(Request $request) {

        $qrcode = $request->qrcode ? $request->qrcode : null;

        if(!$qrcode) {
            return 'no_qrcode';
        }

        $employee = $this->employeeRepository->findEmployeeByQrCode($qrcode);

        if($employee == 'no_employee') {

            $data = [
                'profile' => null,
                'status' => 'no_employee'
            ];
            return  $data;
        }

        $params = [
            'employee' => json_decode(json_encode($employee)),
        ];

        $attendance = $this->attendanceRepository->attendanceByQr(json_decode(json_encode($params)));

        return response()->json($attendance, 200);
    }

    public function otIn($id) {

        $attendance = $this->attendanceRepository->otIn($id);

        return response()->json($attendance, 200);

    }

    public function otOut($id) {

        $attendance = $this->attendanceRepository->otOut($id);

        return response()->json($attendance, 200);

    }

    public function getOvertime(Request $request) {

        $page = $request->page ? $request->page : 1;
        $count = $request->count ? $request->count : 10;
        $search = $request->search && $request->search != '' && $request->search !== 'null' ? $request->search : null;
        $date = $request->date ? $request->date : null;

        $params = [
            'page' => $page,
            'count' => $count,
            'search' => $search,
            'date' => $date,
        ];

        $overtime = $this->attendanceRepository->getOvertime(json_decode(json_encode($params)));

        return response()->json($overtime, 200);
    }

    public function approvedOT($id) {

        $attendance = $this->attendanceRepository->approvedOT($id);

        return response()->json($attendance, 200);

    }

    public function declineOT($id) {

        $attendance = $this->attendanceRepository->declineOT($id);

        return response()->json($attendance, 200);

    }
}
