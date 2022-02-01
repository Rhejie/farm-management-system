<?php

namespace App\Models\Leadman;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;

    public function members() {
        return $this->hasMany(TeamMember::class, 'team_id', 'id');
    }

}
