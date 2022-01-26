<?php

namespace App\Http\Controllers;

use App\Models\HR\Area;
use App\Models\HR\Employee;
use App\Models\Leadman\Attendance;
use App\Models\Leadman\DailyOperation;
use App\Models\Leadman\Team;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function getTotalEmployees() {

        $employees = Employee::count();

        return response()->json($employees, 200);

    }

    public function getTotalAreas() {

        $areas = Area::count();

        return response()->json($areas, 200);

    }

    public function getTotalOperations() {

        date_default_timezone_set('Asia/Manila');

        $date = Carbon::now()->format('Y-m-d');

        $teams = DailyOperation::where('date', $date)->where('is_deploy', 1)->count();

        return response()->json($teams, 200);

    }

    public function getPresentEmployee() {

        date_default_timezone_set('Asia/Manila');


        $date = Carbon::now()->format('Y-m-d');

        $employees = Attendance::where('date', $date)->count();

        return response()->json($employees, 200);

    }


}
