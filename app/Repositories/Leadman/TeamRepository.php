<?php

namespace App\Repositories\Leadman;

use App\Models\Leadman\Team;
use App\Models\Leadman\TeamMember;
use App\Repositories\Repository;
use Carbon\Carbon;

class TeamRepository extends Repository {

    public function __construct(Team $model) {

        parent::__construct($model);

    }

    public function getTeams($params) {

        $teams =  $this->model()->with(['members' => function ($query) {
            $query->with(['employee']);
        }]);

        if($params->search) {

            $teams = $teams->where(function ($query) use ($params) {
                $query->where('name', 'LIKE', "%$params->search%");
            })->orderBy('id', 'desc')->paginate($params->count, ['*'], 'page', $params->page);

            return $teams;

        }

        $teams = $teams->orderBy('id', 'desc')->paginate($params->count, ['*'], 'page', $params->page);

        return $teams;

    }

    public function storeTeams($request) {

        $data = new $this->model();
        $data->name = $request->name;
        $data->description = $request->description;

        if($data->save()) {

            foreach($request->employees as $employee) {
                $member = new TeamMember();
                $member->team_id = $data->id;
                $member->employee_id = $employee->id;
                $member->save();
            }

            return $this->model()->with(['members' => function ($query) {
                $query->with(['employee']);
            }])->find($data->id);

        }

    }

    public function updateTeam($id, $request) {

        $data = $this->model()->find($id);
        $data->name = $request->name;
        $data->description = $request->description;

        if($data->save()) {

            return $data;

        }

    }

    public function deleteTeam($id) {

        $data = $this->model()->find($id);
        $data->delete();

    }

    public function searchTeam($params) {

        $teams =  $this->model();

        if($params->search) {

            $teams = $teams->where(function ($query) use ($params) {
                $query->where('name', 'LIKE', "%$params->search%");
            })->limit(20)->get();

            return $teams;
        }

    }
}
