<?php

namespace App\Repositories\Leadman;

use App\Models\HR\Area;
use App\Models\Leadman\DailyOperation;
use App\Models\Leadman\DeployEmployee;
use App\Repositories\Repository;
use Carbon\Carbon;

class DeployEmployeeRepository extends Repository {

    public function __construct(DeployEmployee $model) {

        parent::__construct($model);

    }

    public function getDeploy($params) {

        $employee =$this->model()->with(['area', 'team'])
        ->where(\DB::raw("(DATE_FORMAT(date,'%d-%m-%Y'))"), (new Carbon($params->date))->format('d-m-Y'));

        if($params->search) {

            $employee = $employee->where(function ($query) use ($params) {
                $query->whereHas('team', function ($query) use ($params) {
                    $query->where(function ($query) use ($params) {
                        $query->orWhere('name', 'LIKE', "%$params->search%");
                    });
                    $query->whereHas('area', function ($query) use ($params) {
                        $query->where(function ($query) use ($params) {
                            $query->where('name', 'LIKE', "%$params->search%");
                        });
                    });
                });
            })->orderBy('id', 'desc')->paginate($params->count, ['*'], 'page', $params->page);

            return $employee;

        }

        $employee = $employee->orderBy('id', 'desc')->paginate($params->count, ['*'], 'page', $params->page);

        return $employee;

    }

    public function getDeployByArea($params) {
        // $employee =$this->model()->with(['area', 'team', 'task' => function ($query) {
        //     $query->with(['task']);
        // }])
        // ->where(\DB::raw("(DATE_FORMAT(date,'%d-%m-%Y'))"), (new Carbon($params->date))->format('d-m-Y'))->get();

        // $employee = $employee->groupBy([function($val) {
        //     return $val->area->name;
        // }]);

        $teams = Area::with(['deployTeam' => function($query){
            $query->with(['team', 'task' => function($query){
                $query->with(['task']);
            }]);
        }])->whereHas('deployTeam', function($query) use ($params) {
            $query->where(function($query) use ($params) {
                $query->where(\DB::raw("(DATE_FORMAT(date,'%d-%m-%Y'))"), (new Carbon($params->date))->format('d-m-Y'));
            });
        })->get();

        return $teams;
    }

    public function storeDeploy($request) {
        $check = $this->model()
            ->where(\DB::raw("(DATE_FORMAT(date,'%d-%m-%Y'))"), (new Carbon($request->date))->format('d-m-Y'))
            ->where('team_id', $request->team_id)->first();

            if(!empty($check)) {

                return 'already_deploy';

            }

        $data = new $this->model();
        $data->team_id = $request->team_id;
        $data->area_id = $request->area_id;
        $data->daily_operation_id = $request->id;
        $data->members = $request->members;
        $data->date = $request->date;

        if($data->save()) {

            $daily = DailyOperation::find($request->id);
            $daily->is_deploy = true;
            $daily->save();

            return $this->model()->with(['area', 'team', 'task' => function ($query) {
                $query->with(['task']);
            }])->find($data->id);
        }

    }

    public function updateDeploy($id, $request) {

        $data = $this->model()->find($id);
        $team_id = $request->team_id_id ? $request->team_id_id : $request->team_id;
        $area_id = $request->area_id_id ? $request->area_id_id : $request->area_id;

        $data->team_id = $team_id;
        $data->area_id = $area_id;
        $data->members = $request->members;
        $data->date = $request->date;

        if($data->save()) {
            return $this->model()->with(['area', 'team'])->find($id);
        }
    }

    public function deleteDeploy($id) {

        $data = $this->model()->find($id);

        $daily = DailyOperation::find($data->daily_operation_id);
            $daily->is_deploy = false;
            $daily->save();

        if($data->delete()) {
            return ' delete';
        }

    }

}
