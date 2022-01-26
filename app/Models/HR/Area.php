<?php

namespace App\Models\HR;

use App\Models\Leadman\DeployEmployee;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    use HasFactory;

    protected $casts = [
        'coordinates' => 'array'
    ];

    public function deployTeam() {

        return $this->hasMany(DeployEmployee::class, 'area_id', 'id');

    }
}
