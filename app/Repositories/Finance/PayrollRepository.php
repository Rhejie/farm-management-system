<?php

namespace App\Repositories\Finance;

use App\Models\Finance\Payroll;
use App\Models\Finance\PayrollItem;
use App\Repositories\Leadman\AttendanceRepository;
use App\Repositories\Repository;
use Carbon\Carbon;

class PayrollRepository extends Repository {

    private $attendanceRepository;

    public function __construct(Payroll $model, AttendanceRepository $attendanceRepository) {

        $this->attendanceRepository = $attendanceRepository;
        parent::__construct($model);

    }

    public function getPayrolls($params) {

        $payrolls = $this->model()->with(['employee']);
            // ->where(\DB::raw("(DATE_FORMAT(to_date,'%d-%m-%Y'))"), (new Carbon($params->date))->format('d-m-Y'));

            if($params->search) {

                $payrolls = $payrolls->where(function($query) use ($params) {
                    $query->whereHas('employee', function ($query) use ($params) {
                        $query->where(function ($query) use ($params) {
                            $query->orWhere('firstname', 'LIKE', "%$params->search%");
                            $query->orWhere('lastname', 'LIKE', "%$params->search%");
                            $query->orWhere('middlename', 'LIKE', "%$params->search%");
                        });
                    });
                })->orderBy('id', 'desc')->paginate($params->count, ['*'], 'page', $params->page);

                return $payrolls;

            }

            $payrolls = $payrolls->orderBy('id', 'desc')->paginate($params->count, ['*'], 'page', $params->page);

            return $payrolls;

    }

    public function storePayroll($request) {

        $data = new $this->model();
        $data->employee_id = $request->employee_id;
        $data->from_date = $request->date_from;
        $data->to_date = $request->date_to;
        $data->rate = $request->rate;
        $data->date = Carbon::now();
        $data->regular = $request->regular;
        $data->overtime = $request->overtime;
        $data->deductions = $request->deductions;
        if($data->save()) {
            return $this->model()->with(['employee'])->find($data->id);
        }

    }

    public function generatePayroll($request) {

        $check = $this->model()->where('employee_id', $request->employee_id)
            ->where('from_date', $request->date_from)
            ->where('to_date', $request->date_to)->first();

        if(!empty($check)) {
            return 'already';
        }
        else {
            // $check2 = $this->model()->where('employee_id', $request->employee_id)
            // ->where('from_date', '' , $request->date_from)
            // ->where('to_date', $request->date_to)->first();
        }

        $attendance = $this->attendanceRepository->geAttendanceByIDdate($request);

        return $attendance;

    }

    public function generatePayslip($id) {


        $payroll = $this->model()->with(['employee', 'item'])->find($id);

        return $payroll;

    }
}
