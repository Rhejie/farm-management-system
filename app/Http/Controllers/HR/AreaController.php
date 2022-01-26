<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Repositories\HR\AreaRepository;
use Illuminate\Http\Request;

class AreaController extends Controller
{
    private $areaRepository;
    public function __construct(AreaRepository $areaRepository) {

        $this->areaRepository = $areaRepository;

    }

    public function index() {

        return view('HR.area.index');

    }

    public function getAreas(Request $request) {

        $page = $request->page ? $request->page : 1;
        $count = $request->count ? $request->count : 10;
        $search = $request->search && $request->search != '' && $request->search !== 'null' ? $request->search : null;

        $params = [
            'page' => $page,
            'count' => $count,
            'search' => $search
        ];

        $areas = $this->areaRepository->getAreas(json_decode(json_encode($params)));

        return response()->json($areas, 200);

    }

    public function storeArea(Request $request) {

        $area = $this->areaRepository->storeArea(json_decode(json_encode($request->all())));

        return response()->json($area, 200);

    }

    public function updateArea($id, Request $request) {

        $area = $this->areaRepository->updateArea($id, json_decode(json_encode($request->all())));

        return response()->json($area, 200);

    }

    public function deleteArea($id) {

        $area = $this->areaRepository->deleteArea($id);

        return response()->json($area, 200);

    }

    public function searchArea(Request $request) {

        $search = $request->search ? $request->search : null;

        $params = [
            'search' => $search
        ];

        $areas = $this->areaRepository->searchArea(json_decode(json_encode($params)));

        return response()->json($areas, 200);

    }

    public function getAreasList() {

        $areas = $this->areaRepository->getAreasList();

        return response()->json($areas, 200);
    }

    public function getArea($id) {

        $area = $this->areaRepository->getAreaById($id);

        return response()->json($area, 200);
    }
}
