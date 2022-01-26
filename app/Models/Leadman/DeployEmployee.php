<?php

namespace App\Models\Leadman;

use App\Models\HR\Area;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeployEmployee extends Model
{
    use HasFactory;

    protected $casts = [
        'members' => 'array'
    ];

    public function team() {
        return $this->belongsTo(Team::class, 'team_id', 'id');
    }

    public function area() {
        return $this->belongsTo(Area::class, 'area_id', 'id');
    }

    public function task() {

        return $this->belongsTo(DailyOperation::class, 'daily_operation_id', 'id');

    }
}
