<?php

namespace App\Repositories\Leadman;

use App\Models\Leadman\HalfMonth;
use App\Repositories\Repository;
use Carbon\Carbon;

class HalfMonthRepository extends Repository {

    public function __construct(HalfMonth $model) {

        parent::__construct($model);

    }

    public function getReports($params) {

        $reports = $this->model();

        if($params->search) {

            $reports = $reports->where(function($query) use ($params) {

            })->orderBy('id', 'desc')->paginate($params->count, ['*'], 'page', $params->page);

            return $reports;
        }

        $reports = $reports->orderBy('id', 'desc')->paginate($params->count, ['*'], 'page', $params->page);

        return $reports;
    }

    public function storeReport($request) {

        $data = new $this->model();
        $data->date = Carbon::now();
        $data->from_date = $request->date_from;
        $data->to_date = $request->date_to;
        $data->tasks = $request->tasks;

        if($data->save()) {
            return $data;
        }
    }


    public function deleteReport($id) {

        $data = $this->model()->find($id);
        $data->delete();

    }

    public function checkifExist($request) {

        $check = $this->model()
            ->where('from_date', $request->date_from)
            ->where('to_date', $request->date_to)->first();

        if(!empty($check)) {
            return 'already';
        }

    }

    public function getReportById($id) {

        $report = $this->model()->find($id);

        return $report;

    }
}
