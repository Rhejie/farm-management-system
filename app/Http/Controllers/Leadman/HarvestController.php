<?php

namespace App\Http\Controllers\Leadman;

use App\Http\Controllers\Controller;
use App\Repositories\Leadman\HarvestRepository;
use Illuminate\Http\Request;

class HarvestController extends Controller
{

    private $harvestRepository;

    public function __construct(HarvestRepository $harvestRepository) {

        $this->harvestRepository = $harvestRepository;

    }

    public function index() {

        return view('leadman.harvest.index');

    }

    public function getHarvest(Request $request) {
        $page = $request->page ? $request->page : 1;
        $count = $request->count ? $request->count : 10;
        $search = $request->search && $request->search != '' && $request->search !== 'null' ? $request->search : null;

        $params = [
            'page' => $page,
            'count' => $count,
            'search' => $search
        ];

        $harvest = $this->harvestRepository->getHarvest(json_decode(json_encode($params)));

        return response()->json($harvest, 200);
    }

    public function storeHarvest(Request $request) {

        $harvest = $this->harvestRepository->storeHarvest(json_decode(json_encode($request->all())));

        return response()->json($harvest, 200);
    }

    public function updateHarvest($id, Request $request) {

        $harvest = $this->harvestRepository->updateHarvest($id, json_decode(json_encode($request->all())));

        return response()->json($harvest, 200);

    }

    public function deleteHarvest($id) {

        $harvest = $this->harvestRepository->deleteHarvest($id);

        return response()->json($harvest, 200);

    }
}
