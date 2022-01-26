<?php

namespace App\Http\Controllers\Leadman;

use App\Http\Controllers\Controller;
use App\Repositories\Leadman\DailyOperationRepository;
use Illuminate\Http\Request;

class DailyOperationController extends Controller
{

    private $dailyOperationRepository ;
    public function __construct(DailyOperationRepository $dailyOperationRepository) {

        $this->dailyOperationRepository = $dailyOperationRepository;

    }

    public function index() {

        return view('leadman.operation.index');

    }

    public function getOperations(Request $request) {

        $page = $request->page ? $request->page : 1;
        $count = $request->count ? $request->count : 10;
        $search = $request->search && $request->search != '' && $request->search !== 'null' ? $request->search : null;
        $date = $request->date ? $request->date : null;

        $params = [
            'page' => $page,
            'count' => $count,
            'search' => $search,
            'date' => $date,
        ];

        $operations = $this->dailyOperationRepository->getOperations(json_decode(json_encode($params)));

        return response()->json($operations, 200);

    }

    public function getUndeployedOperations(Request $request) {

        $search = $request->search && $request->search != '' && $request->search !== 'null' ? $request->search : null;
        $date = $request->date ? $request->date : null;

        $params = [
            'search' => $search,
            'date' => $date,
        ];

        $operations = $this->dailyOperationRepository->getUndeployedOperations(json_decode(json_encode($params)));

        return response()->json($operations, 200);
    }

    public function storeOperation(Request $request) {

        $operation = $this->dailyOperationRepository->storeOperation(json_decode(json_encode($request->all())));

        return response()->json($operation, 200);

    }

    public function updateOperaion($id, Request $request) {

        $operation = $this->dailyOperationRepository->updateOperation($id, json_decode(json_encode($request->all())));

        return response()->json($operation, 200);

    }

    public function deleteOperation($id) {

        $operation = $this->dailyOperationRepository->deleteOperation($id);

        return response()->json($operation, 200);

    }
}
