<?php

namespace App\Http\Controllers\Leadman;

use App\Models\Leadman\Harvest;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BananaYieldReportController extends Controller
{

    public function index() {

        return view('banana.index');
    }

    public function expectedHarvest($id) {

        $current_year = Carbon::now()->format('Y');

        $years = Harvest::select(\DB::raw("DATE_FORMAT(date,'%Y') as year"))->groupBy('year')->get()->pluck('year');
        $sample_data = [];
        $harvest_data = [];

        if($id) {
            foreach($years as $year) {
                if($year) {
                    if($year != $current_year) {
                        $harvest = Harvest::select(\DB::raw("DATE_FORMAT(date,'%M') as month"),
                                            \DB::raw("AVG(stem_cut) as stem_cut"))->where('arae_id', $id)
                            ->where(\DB::raw("(DATE_FORMAT(date,'%Y'))"), $year)->groupBy('Month')
                            ->get();

                        array_push($sample_data, $harvest);
                        array_push($harvest_data, $harvest);
                    }
                }
            }
            $new_array = [];
            foreach($sample_data as $key => $data) {
                foreach($data as $d) {
                    array_push($new_array, $d);
                }
            }

            $posibleData = \collect($new_array)->groupBy('month')
                ->map(function ($item) {
                    return [$item[0]['month'], $item->avg->stem_cut];
                });

            $months = [];
            $stem_cut = [];

            foreach($posibleData as $data) {
                array_push($months, $data[0]);
                array_push($stem_cut, $data[1]);
            }

            return response()->json([ 'months' => $months, 'stem_cut' => $stem_cut, 'sampleData' => $sample_data], 200);
        }

    }

    public function getData($id) {

        if($id) {
            $harvest_data = Harvest::with('area')->where('arae_id', $id)->get();

            $harvest_data = $harvest_data->groupBy(function($val) {
                return Carbon::parse($val['date'])->format('Y');
            });

            return response()->json($harvest_data, 200);
        }
    }
}
