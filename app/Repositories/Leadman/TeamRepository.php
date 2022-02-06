<?php

namespace App\Repositories\Leadman;

use App\Models\Leadman\Team;
use App\Models\Leadman\TeamMember;
use App\Repositories\Repository;
use Carbon\Carbon;

class TeamRepository extends Repository {

    private $dailyOperationRepository;

    public function __construct(Team $model, DailyOperationRepository $dailyOperationRepository) {
        $this->dailyOperationRepository = $dailyOperationRepository;
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
            foreach($request->employees as $employee) {

                if($employee->status && $employee->status == 'new') {

                    $member = new TeamMember();
                    $member->team_id = $data->id;
                    $member->employee_id = $employee->id;
                    $member->save();
                }

            }

            foreach ($request->remove_members as $member) {

                $member = TeamMember::where('employee_id', $member)->where('team_id', $data->id)->first();
                if(!empty($member)) $member->delete();
            }

            $updatedTeam = $this->model()->with(['members' => function ($query) {
                $query->with(['employee']);
            }])->find($data->id);

            $this->dailyOperationRepository->updateTeamAndMeber($updatedTeam);

            return $updatedTeam;

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
            })->with('members')->limit(20)->get();

            return $teams;
        }

    }
}
