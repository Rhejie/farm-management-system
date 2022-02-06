<?php
namespace App\Repositories\Leadman;

use App\Models\Leadman\LogisticRegression;
use App\Repositories\Repository;
use Carbon\Carbon;

class LogisticRegressionRepository extends Repository {

    public function __construct(LogisticRegression $model) {

        parent::__construct($model);

    }

    public function getLogisticRegresions($params) {

        $data = $this->model()->with(['area']);

        if($params->area_id && $params->area_id != 'All') {

            $data = $data->where('area_id', $params->area_id);

        }

        if($params->search) {

            $data = $data->orderBy('id', 'desc')->paginate($params->count, ['*'], 'page', $params->page);

            return $data;

        }

        $data = $data->orderBy('id', 'desc')->paginate($params->count, ['*'], 'page', $params->page);

        return $data;

    }

    public function storeLogistic($request) {

        $data = new $this->model();

        $data->bud_injection_x1 = $request->bud_injection_x1;
        $data->area_id = $request->area_id;
        $data->bu_injection_date = $request->bu_injection_date;
        $data->bagging_report_x2 = $request->bagging_report_x2;
        $data->bagging_report_date = $request->bagging_report_date;
        $data->stem_cut_y = $request->stem_cut_y;
        $data->stem_cut_y_date = $request->stem_cut_y_date;
        if($data->save()) {

            return $this->model()->with(['area'])->find($data->id);

        }

    }

    public function updateLogistic($id, $request) {

        $area_id = $request->area_id_id ? $request->area_id_id : $request->area_id;

        $data = $this->model()->find($id);

        $data->bud_injection_x1 = $request->bud_injection_x1;
        $data->bu_injection_date = $request->bu_injection_date;
        $data->bagging_report_x2 = $request->bagging_report_x2;
        $data->area_id = $area_id;
        $data->bagging_report_date = $request->bagging_report_date;
        $data->stem_cut_y = $request->stem_cut_y;
        $data->stem_cut_y_date = $request->stem_cut_y_date;

        if($data->save()) {

            return  $this->model()->with(['area'])->find($id);

        }
    }

    public function deleteLogistic($id)
    {

        $this->model()->find($id)->delete();

    }

    public function getData($id) {


        $logistics = $this->model()->where('area_id', $id)->get();

        if(!$logistics->isEmpty()) {

            $countLogistic = $logistics->count();

            $sum_x1 = 0;
            $sum_x2 = 0;

            foreach ($logistics as $key => $value) {
                $sum_x1 += $value->bud_injection_x1;
                $sum_x2 += $value->bagging_report_x2;
            }

            $m1 = $sum_x1 / $countLogistic;
            $m2 = $sum_x2 / $countLogistic;

            $logistics->map(function($log) use ($m1, $m2) {

                $log->x1m1 = $log->bud_injection_x1 - $m1;
                $log->x2m2 = $log->bagging_report_x2 - $m2;
                $log->x1m12 = $log->x1m1 * $log->x1m1;
                $log->x2m22 = $log->x2m2 * $log->x2m2;

                return $log;
            });

            $avr_x1m12 = 0;
            $avr_x2m22 = 0;

            foreach ($logistics as $key => $value) {
                $avr_x1m12 += $value->x1m12;
                $avr_x2m22 += $value->x2m22;
            }

            $q1 = sqrt($avr_x1m12 / $countLogistic);
            $q2 = sqrt($avr_x2m22 / $countLogistic);

            $b1 = $q1 / $m1;
            $b2 = $q2 / $m2;

            $logistics->map(function ($log) use ($b1, $b2) {
                $log->y = round((($b1 * $log->bud_injection_x1) + ($b2 * $log->bagging_report_x2)), 0);
            });

            return $logistics;
        }

        return [];
        // return [
        //     'logistics' => $logistics,
        //     'count' => $countLogistic,
        //     'sum_x1' => $sum_x1,
        //     'sum_x2' => $sum_x2,
        //     'm1' => $m1,
        //     'm2' => $m2,
        //     'avr_x1m12' => $avr_x1m12,
        //     'avr_x2m22' => $avr_x2m22,
        //     'q1' => $q1,
        //     'q2' => $q2,
        //     'b1' => $b1,
        //     'b2' => $b2,
        // ];

    }
}
