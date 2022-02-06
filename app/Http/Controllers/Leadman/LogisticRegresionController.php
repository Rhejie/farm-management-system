<?php

namespace App\Http\Controllers\Leadman;

use App\Http\Controllers\Controller;
use App\Repositories\Leadman\LogisticRegressionRepository;
use Illuminate\Http\Request;

class LogisticRegresionController extends Controller
{

    private $logisticRegressionRepository;

    public function __construct(LogisticRegressionRepository $logisticRegressionRepository) {

        $this->logisticRegressionRepository = $logisticRegressionRepository;

    }

    public function index() {

        return view('leadman.logistic.index');

    }

    public function getLogistics() {

        $logistics = $this->logisticRegressionRepository->getLogisticRegresions();

        return response()->json($logistics, 200);

    }

    public function storeLogistic(Request $request) {

        $data = $this->logisticRegressionRepository->storeLogistic(json_decode(json_encode($request->all())));

        return response()->json($data, 200);

    }

    public function updateLogistic($id, Request $request) {

        $data = $this->logisticRegressionRepository->updateLogistic($id, json_decode(json_encode($request->all())));

        return response()->json($data, 200);

    }

    public function deleteLogistic($id) {

        $data = $this->logisticRegressionRepository->deleteLogistic($id);

        return response()->json($data, 200);

    }
}
