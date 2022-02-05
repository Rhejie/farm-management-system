<?php

namespace App\Repositories\Leadman;

use App\Models\Leadman\DailyOperation;
use App\Models\Leadman\DailyOperationTeam;
use App\Models\Leadman\DailyOperationTeamMember;
use App\Models\Leadman\Team;
use App\Repositories\Repository;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DailyOperationRepository extends Repository {

    public function __construct(DailyOperation $model) {

        parent::__construct($model);

    }

    public function getOperations($params) {

        $operations = $this->model()->with(['dailyOperationTeam' => function($query) {
            $query->with(['dailyOperationTeamMember' => function($query) {
                $query->with(['employee']);
            }]);
        },'team', 'area', 'task'])
            ->where(DB::raw("(DATE_FORMAT(date,'%d-%m-%Y'))"), (new Carbon($params->date))->format('d-m-Y'));

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

        $operations = $this->model()->with(['dailyOperationTeam' => function($query) {
            $query->with(['dailyOperationTeamMember' => function($query) {
                $query->with(['employee']);
            }]);
        },'team', 'area', 'task'])
            ->where(DB::raw("(DATE_FORMAT(date,'%d-%m-%Y'))"), (new Carbon($params->date))->format('d-m-Y'))
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

        if($data->save()) {

            $team_info = Team::with(['members'])->find($data->team_id);

            $team = new DailyOperationTeam();
            $team->daily_operation_id = $data->id;
            $team->team_id = $data->team_id;
            $team->name = $team_info->name;
            $team->description = $team_info->description;

            if($team->save()) {

                foreach($team_info->members as $member) {

                    $team_member = new DailyOperationTeamMember();
                    $team_member->d_o_team_id = $team->id;
                    $team_member->employee_id = $member->employee_id;
                    $team_member->save();

                }

            }

            return $this->model()->with(['dailyOperationTeam' => function($query) {
                $query->with(['dailyOperationTeamMember' => function($query) {
                    $query->with(['employee']);
                }]);
            }, 'area', 'task'])->find($data->id);

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
        $team = DailyOperationTeam::where('daily_operation_id', $id)->first();
        DailyOperationTeamMember::where('d_o_team_id', $team->id)->delete();
        DailyOperationTeam::where('daily_operation_id', $id)->delete();
        $data->delete();

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
