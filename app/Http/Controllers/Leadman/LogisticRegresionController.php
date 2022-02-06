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

    public function getLogistics(Request $request) {
        $page = $request->page ? $request->page : 1;
        $area_id = $request->area_id ? $request->area_id : 'All';
        $count = $request->count ? $request->count : 10;
        $search = $request->search && $request->search != '' && $request->search !== 'null' ? $request->search : null;

        $params = [
            'page' => $page,
            'count' => $count,
            'search' => $search,
            'area_id' => $area_id
        ];
        $logistics = $this->logisticRegressionRepository->getLogisticRegresions(json_decode(json_encode($request->all())));

        $logistics->map(function($d) {

            $d->x1y = $d->bud_injection_x1 * $d->stem_cut_y;
            $d->x2y = $d->bagging_report_x2 * $d->stem_cut_y;
            $d->x1x2 = $d->bud_injection_x1 * $d->bagging_report_x2;
            $d->x12 = $d->bud_injection_x1 * $d->bud_injection_x1;
            $d->x22 = $d->bagging_report_x2 * $d->bagging_report_x2;
            $d->y22 = $d->stem_cut_y * $d->stem_cut_y;

            return $d;
        });

        return response()->json($logistics, 200);

    }

    public function storeLogistic(Request $request) {

        $data = $this->logisticRegressionRepository->storeLogistic(json_decode(json_encode($request->all())));

        $data->x1y = $data->bud_injection_x1 * $data->stem_cut_y;
        $data->x2y = $data->bagging_report_x2 * $data->stem_cut_y;
        $data->x1x2 = $data->bud_injection_x1 * $data->bagging_report_x2;
        $data->x12 = $data->bud_injection_x1 * $data->bud_injection_x1;
        $data->x22 = $data->bagging_report_x2 * $data->bagging_report_x2;
        $data->y22 = $data->stem_cut_y * $data->stem_cut_y;

        return response()->json($data, 200);

    }

    public function updateLogistic($id, Request $request) {

        $data = $this->logisticRegressionRepository->updateLogistic($id, json_decode(json_encode($request->all())));

        $data->x1y = $data->bud_injection_x1 * $data->stem_cut_y;
        $data->x2y = $data->bagging_report_x2 * $data->stem_cut_y;
        $data->x1x2 = $data->bud_injection_x1 * $data->bagging_report_x2;
        $data->x12 = $data->bud_injection_x1 * $data->bud_injection_x1;
        $data->x22 = $data->bagging_report_x2 * $data->bagging_report_x2;
        $data->y22 = $data->stem_cut_y * $data->stem_cut_y;

        return response()->json($data, 200);

    }

    public function deleteLogistic($id) {

        $data = $this->logisticRegressionRepository->deleteLogistic($id);

        return response()->json($data, 200);

    }

    public function getData($id) {

        $data = $this->logisticRegressionRepository->getData($id);

        return response()->json($data, 200);

    }
}
