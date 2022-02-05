<?php

namespace App\Models\Leadman;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyOperationTeam extends Model
{
    use HasFactory;

    public function dailyOperationTeamMember() {
        return $this->hasMany(DailyOperationTeamMember::class, 'd_o_team_id', 'id');
    }
}
