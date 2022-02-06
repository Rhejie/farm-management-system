<?php

namespace App\Models\HR;

use App\Models\Leadman\TeamMember;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    public function teamMember() {

        return $this->belongsTo(TeamMember::class, 'id', 'employee_id');

    }
}
