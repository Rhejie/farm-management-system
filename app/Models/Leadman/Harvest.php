<?php

namespace App\Models\Leadman;

use App\Models\HR\Area;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Harvest extends Model
{
    use HasFactory;

    public function area() {
        return $this->belongsTo(Area::class, 'arae_id', 'id');
    }
}
