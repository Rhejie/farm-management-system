<?php

namespace App\Models\Leadman;

use App\Models\HR\Employee;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    public function employee() {
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }

    protected $cast = [
        'date_time_in' => ''
    ];
}
