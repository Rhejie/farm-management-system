<?php

namespace App\Repositories\Leadman;

use App\Models\Leadman\DailyOperation;
use App\Repositories\Repository;
use Carbon\Carbon;

class DailyOperationRepository extends Repository {

    public function __construct(DailyOperation $model) {

        parent::__construct($model);

    }

    public function getOperations($params) {

        $operations = $this->model()->with(['team', 'area', 'task'])
            ->where(\DB::raw("(DATE_FORMAT(date,'%d-%m-%Y'))"), (new Carbon($params->date))->format('d-m-Y'));

        if($params->search) {

            $operations = $operations->where(function($query) use ($params) {
                $query->whereHas('team', function($query) use ($params) {
                    $query->where(function($query) use ($params) {
                        $query->where('name', 'LIKE', "%$params->search%");
                    });
                });
                $query->whereHas('area', function($query) use ($params) {
                    $query->where(function($query) use ($params) {
                        $query->where('name', 'LIKE', "%$params->search%");
                    });
                });
                $query->whereHas('task', function($query) use ($params) {
                    $query->where(function($query) use ($params) {
                        $query->where('name', 'LIKE', "%$params->search%");
                    });
                });
            })->orderBy('id', 'desc')->paginate($params->count, ['*'], 'page', $params->page);

            return $operations;

        }

        $operations = $operations->orderBy('id', 'desc')->paginate($params->count, ['*'], 'page', $params->page);

        return $operations;
    }

    public function getUndeployedOperations($params) {

        $operations = $this->model()->with(['team', 'area', 'task'])
            ->where(\DB::raw("(DATE_FORMAT(date,'%d-%m-%Y'))"), (new Carbon($params->date))->format('d-m-Y'))
            ->where('is_deploy', 0);

        if($params->search) {
            $operations = $operations->where(function($query) use ($params) {
                $query->whereHas('team', function($query) use ($params) {
                    $query->where(function($query) use ($params) {
                        $query->where('name', 'LIKE', "%$params->search%");
                    });
                });
                $query->whereHas('area', function($query) use ($params) {
                    $query->where(function($query) use ($params) {
                        $query->where('name', 'LIKE', "%$params->search%");
                    });
                });
                $query->whereHas('task', function($query) use ($params) {
                    $query->where(function($query) use ($params) {
                        $query->where('name', 'LIKE', "%$params->search%");
                    });
                });
            })->get();

            return $operations;

        }

        $operations = $operations->get();

        return $operations;


    }

    public function storeOperation($request) {

        $check = $this->model()->where('date', $request->date)->where('team_id', $request->team_id)->first();

        if(!empty($check)) {

            return 'already_time_in';

        }

        $data = new $this->model();
        $data->team_id = $request->team_id;
        $data->area_id = $request->area_id;
        $data->task_id = $request->task_id;
        $data->date = $request->date;
        $data->members = $request->members;

        if($data->save()) {

            return $this->model()->with(['team', 'area', 'task'])->find($data->id);

        }

    }

    public function updateOperation($id, $request) {

        $data = $this->model()->find($id);

        $team_id = $request->team_id_id ? $request->team_id_id : $request->team_id;
        $area_id = $request->area_id_id ? $request->area_id_id : $request->area_id;

        $data->team_id = $team_id;
        $data->area_id = $area_id;
        $data->date = $request->date;
        $data->members = $request->members;

        if($data->save()) {

            return $this->model()->with(['team', 'area', 'task'])->find($id);

        }

    }

    public function deleteOperation($id) {

        $data = $this->model()->find($id);
        $data->save();

    }

    public function generateReport($request) {

        $generate = $this->model()
                ->with(['team', 'area', 'task'])
                ->where('date', '>=', $request->date_from)
                ->where('date', '<=', $request->date_to)
                ->where('is_deploy', 1)
                ->get();

        return $generate;

    }

}
