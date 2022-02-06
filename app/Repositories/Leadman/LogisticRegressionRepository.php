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

        $data = $this->model();

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
        $data->bu_injection_date = $request->bu_injection_date;
        $data->bagging_report_x2 = $request->bagging_report_x2;
        $data->bagging_report_date = $request->bagging_report_date;
        $data->stem_cut_y = $request->stem_cut_y;
        $data->stem_cut_y_date = $request->stem_cut_y_date;
        if($data->save()) {

            return $data;

        }

    }

    public function updateLogistic($id, $request) {

        $data = $this->model()->find($id);

        $data->bud_injection_x1 = $request->bud_injection_x1;
        $data->bu_injection_date = $request->bu_injection_date;
        $data->bagging_report_x2 = $request->bagging_report_x2;
        $data->bagging_report_date = $request->bagging_report_date;
        $data->stem_cut_y = $request->stem_cut_y;
        $data->stem_cut_y_date = $request->stem_cut_y_date;

        if($data->save()) {

            return $data;

        }
    }

    public function deleteLogistic($id)
    {

        $this->model()->find($id)->delete();

    }
}
