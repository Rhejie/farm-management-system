<?php

namespace App\Repositories\Leadman;

use App\Models\Leadman\Attendance;
use App\Repositories\Repository;
use Carbon\Carbon;

class AttendanceRepository extends Repository {

    public function __construct(Attendance $model) {

        parent::__construct($model);

    }

    public function getAttendance($params) {

        $attendance = $this->model()->with(['employee']);

        if($params->search) {

            $attendance = $attendance->where(function($query) use ($params) {
                $query->where('date', 'LIKE', "$params->date");
                $query->whereHas('employee', function ($query) use ($params) {
                    $query->where(function ($query) use ($params) {
                        $query->orWhere('firstname', 'LIKE', "%$params->search%");
                        $query->orWhere('middlename', 'LIKE', "%$params->search%");
                        $query->orWhere('lastname', 'LIKE', "%$params->search%");
                    });
                });
            })->orderBy('id', 'desc')->paginate($params->count, ['*'], 'page', $params->page);

            return $attendance;

        }

        $attendance = $attendance->where('date', 'LIKE', "$params->date")
                ->orderBy('id', 'desc')->paginate($params->count, ['*'], 'page', $params->page);

        return $attendance;

    }

    public function storeAttendance($request) {

        $check = $this->model()->where('date', $request->date)->where('employee_id', $request->employee_id)->first();

        if(!empty($check)) {

            return 'already_time_in';

        }

        $data = new $this->model();

        $data->employee_id = $request->employee_id;
        $data->date = $request->date;
        $data->time_in = $request->time_in;

        if($data->save()) {

            return $this->model()->with(['employee'])->find($data->id);

        }

    }

    public function updateAttendance($id, $request) {

        date_default_timezone_set('Asia/Manila');

        $data = $this->model()->find($id);

        if($request->time_out) {
            $time = $request->time_out;
        }
        else {

            $time = Carbon::now()->format('H:i:s');

        }

        $data->time_out = $time;

        if($data->save()) {

            return $this->model()->with(['employee'])->find($id);

        }

    }

    public function attendanceByQr($params) {
        date_default_timezone_set('Asia/Manila');

        $date_now = carbon::now()->format('Y-m-d');
        $time_now = Carbon::now()->format('H:i:s');

        $attendance = $this->model()->with(['employee'])->where('date', $date_now)->where('employee_id', $params->employee->id)->first();

        if(empty($attendance)) {
            $newAttendance = new $this->model();
            $newAttendance->date = $date_now;
            $newAttendance->employee_id = $params->employee->id;
            $newAttendance->time_in = $time_now;
            if($newAttendance->save()) {
                $data = [
                    'profile' => $this->model()->with(['employee'])->find($newAttendance->id),
                    'status' => 'success_in'
                ];
                return $data;
            }
        }
        else {

            if(!$attendance->time_in) {

                $attendance->time_in = $time_now;

                if($attendance->save()) {
                    $data = [
                        'profile' => $attendance,
                        'status' => 'success_in'
                    ];
                }
            }
            else if(!$attendance->time_out) {

                $attendance->time_out = $time_now;

                if($attendance->save()) {
                    $data = [
                        'profile' => $attendance,
                        'status' => 'success_out'
                    ];
                    return $data;
                }

            }
            else if(!$attendance->ot_in) {

                $from = Carbon::createFromFormat('H:s:i', $attendance->time_in);
                $to = Carbon::createFromFormat('H:s:i', $attendance->time_out);

                $diff_in_hours = $to->diffInHours($from);

                if($diff_in_hours == 8) {
                    $diff_in_hours += 1;
                }

                if($diff_in_hours < 9) {
                    $data = [
                        'profile' => $attendance,
                        'status' => 'invalid'
                    ];

                    return $data;
                }

                $attendance->ot_in = $time_now;

                if($attendance->save()) {

                    $data = [
                        'profile' => $attendance,
                        'status' => 'success_ot_in'
                    ];

                    return $data;
                }

            }
            else if(!$attendance->ot_out) {

                $attendance->ot_out = $time_now;

                if($attendance->save()) {

                    $data = [
                        'profile' => $attendance,
                        'status' => 'success_ot_out'
                    ];

                    return $data;
                }
            }
            else {

                $data = [
                    'profile' => $attendance,
                    'status' => 'already_time_in_out'
                ];

                return $data;
            }

        }

    }

    public function geAttendanceByIDdate($request) {

        $attendance = $this->model()->where('employee_id', $request->employee_id)
            ->where('date', '>=', $request->date_from)
            ->where('date', '<=', $request->date_to)
            ->get();

        $attendance = $attendance->map(function($data) {

            $time_date_in = new Carbon($data->date." ".$data->time_in);
            $time_date_out = new Carbon($data->date." ".$data->time_out);


            $ot_date_in = new Carbon($data->date." ".$data->ot_in);
            $ot_date_out = new Carbon($data->date." ".$data->ot_out);

            $diff_in_hours = $time_date_in->diffInHours($time_date_out);
            $diff_in_hours_ot = $ot_date_in->diffInHours($ot_date_out);

            $data->total_hours = $diff_in_hours;
            $data->total_hours_ot = $diff_in_hours_ot;

            $data->date_time_in = $time_date_in;
            $data->date_time_out = $time_date_out;

            if($diff_in_hours == 9 || $diff_in_hours >= 9) {
                $data->status = 'Full';
            }
            if($diff_in_hours == 4 || $diff_in_hours < 5) {
                $data->status = 'Half Day';
            }
            if($diff_in_hours < 4 || $diff_in_hours < 9) {
                $data->status = 'Under Time';
            }

            $data->diff_in_hours = $diff_in_hours;

            return $data;
        });

        $data = [
            'attendance' => $attendance,
            'employee' => $request
        ];

        return $data;


    }

    public function otIn($id) {

        date_default_timezone_set('Asia/Manila');

        $attendance = $this->model()->find($id);

        $from = Carbon::createFromFormat('H:s:i', $attendance->time_in);
        $to = Carbon::createFromFormat('H:s:i', $attendance->time_out);

        $diff_in_hours = $to->diffInHours($from);

        if($diff_in_hours >= 9) {
            $attendance->ot_in = Carbon::now();
            $attendance->save();
            return $this->model()->with(['employee'])->find($id);
        }
        else {
            return 'failed';
        }

    }

    public function otOut($id) {

        date_default_timezone_set('Asia/Manila');

        $attendance = $this->model()->find($id);

        $attendance->ot_out = Carbon::now();

        $attendance->save();

        return $this->model()->with(['employee'])->find($id);
    }

    public function getOvertime($params) {

        $attendance = $this->model()->with(['employee']);

        if($params->search) {

            $attendance = $attendance->where(function($query) use ($params) {
                $query->where('date', 'LIKE', "$params->date");
                $query->whereHas('employee', function ($query) use ($params) {
                    $query->where(function ($query) use ($params) {
                        $query->orWhere('firstname', 'LIKE', "%$params->search%");
                        $query->orWhere('middlename', 'LIKE', "%$params->search%");
                        $query->orWhere('lastname', 'LIKE', "%$params->search%");
                    });
                });
            })->where('ot_in', '!=', null)
            ->where('ot_out', '!=', null)
            ->orderBy('id', 'desc')->paginate($params->count, ['*'], 'page', $params->page);

            return $attendance;

        }

        $attendance = $attendance->where('date', 'LIKE', "$params->date")
                ->where('ot_in', '!=', null)
                ->where('ot_out', '!=', null)
                ->orderBy('id', 'desc')->paginate($params->count, ['*'], 'page', $params->page);

        return $attendance;

    }

    public function approvedOT($id) {

        $attendance = $this->model()->find($id);
        $attendance->ot_status = 'Approved';
        $attendance->save();

        return $this->model()->with(['employee'])->find($id);

    }

    public function declineOT($id) {

        $attendance = $this->model()->find($id);
        $attendance->ot_status = 'Declined';
        $attendance->save();

        return $this->model()->with(['employee'])->find($id);

    }
}
