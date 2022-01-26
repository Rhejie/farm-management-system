<?php

namespace App\Http\Controllers\Leadman;

use App\Http\Controllers\Controller;
use App\Repositories\Leadman\TeamRepository;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    private $teamRepository;

    public function __construct(TeamRepository $teamRepository) {

        $this->teamRepository = $teamRepository;

    }

    public function index() {

        return view('leadman.team.index');

    }

    public function getTeams(Request $request) {

        $page = $request->page ? $request->page : 1;
        $count = $request->count ? $request->count : 10;
        $search = $request->search && $request->search != '' && $request->search !== 'null' ? $request->search : null;

        $params = [
            'page' => $page,
            'count' => $count,
            'search' => $search
        ];

        $teams = $this->teamRepository->getTeams(json_decode(json_encode($params)));

        return response()->json($teams, 200);

    }

    public function storeTeam(Request $request) {

        $team = $this->teamRepository->storeTeams(json_decode(json_encode($request->all())));

        return response()->json($team, 200);
    }

    public function updateTeam($id, Request $request) {

        $team = $this->teamRepository->updateTeam($id, json_decode(json_encode($request->all())));

        return response()->json($team, 200);

    }

    public function deleteTeam($id) {

        $team = $this->teamRepository->deleteTeam($id);

        return response()->json($team, 200);

    }

    public function searchTeam(Request $request) {

        $search = $request->search ? $request->search : null;

        $params = [
            'search' => $search
        ];

        $team = $this->teamRepository->searchTeam(json_decode(json_encode($params)));

        return response()->json($team, 200);

    }
}
