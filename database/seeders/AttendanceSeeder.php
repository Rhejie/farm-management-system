<?php

namespace Database\Seeders;

use App\Models\HR\Employee;
use App\Models\Leadman\Attendance;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class AttendanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $employees = Employee::get();

        foreach($employees as $employee) {

            $days = 0 ;
            date_default_timezone_set('Asia/Manila');
            $dt = new \DateTime('2022-01-1 08:00:00');
            $date_now = Carbon::instance($dt);

            while ($days < 15) {

                $attendance = new Attendance();
                $attendance->employee_id = $employee->id;
                $attendance->date = $date_now;
                $attendance->time_in = Carbon::instance($date_now)->format('H:i:s');
                $attendance->time_out = Carbon::instance($date_now)->addHours(9)->format('H:i:s');
                $attendance->save();

                $days++;
                $date_now = $date_now->addDays(1);
            }

        }
    }
}
