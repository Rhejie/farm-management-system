<?php

namespace App\Http\Controllers\Leadman;

use App\Http\Controllers\Controller;
use App\Repositories\Leadman\DailyOperationRepository;
use App\Repositories\Leadman\HalfMonthRepository;
use Illuminate\Http\Request;

class HalfMonthController extends Controller
{
    private $dailyOperationRepository;
    private $halfMonthRepository;

    public function __construct(DailyOperationRepository $dailyOperationRepository, HalfMonthRepository $halfMonthRepository) {

        $this->halfMonthRepository = $halfMonthRepository;

        $this->dailyOperationRepository = $dailyOperationRepository;

    }

    public function index() {

        return view('leadman.month.index');

    }

    public function getReports(Request $request) {

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

        $reports = $this->halfMonthRepository->getReports(json_decode(json_encode($params)));

        return response()->json($reports, 200);

    }

    public function generateReport(Request $request) {

        $check = $this->halfMonthRepository->checkifExist(json_decode(json_encode($request->all())));

        if($check == 'already') {

            return 'already';

        }

        $reports  = $this->dailyOperationRepository->generateReport(json_decode(json_encode($request->all())));

        $data = [
            'reports' => $reports,
            'data' => $request->all(),
        ];

        return response()->json($data, 200);

    }

    public function storeReport(Request $request) {

        $report = $this->halfMonthRepository->storeReport(json_decode(json_encode($request->all())));

        return response()->json($report, 200);

    }

    public function getReport($id) {

        $report = $this->halfMonthRepository->getReportById($id);

        return response()->json($report, 200);

    }
}
