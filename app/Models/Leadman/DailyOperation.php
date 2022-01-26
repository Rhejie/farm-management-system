<?php

namespace App\Models\Leadman;

use App\Models\HR\Area;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyOperation extends Model
{
    use HasFactory;

    protected $casts = [
        'members' => 'array'
    ];

    public function area() {
        return $this->belongsTo(Area::class, 'area_id', 'id');
    }

    public function task() {
        return $this->belongsTo(Task::class, 'task_id', 'id');
    }

    public function team() {
        return $this->belongsTo(Team::class, 'team_id', 'id');
    }
}
