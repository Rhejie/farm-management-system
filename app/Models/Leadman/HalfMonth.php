<?php

namespace App\Models\Leadman;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HalfMonth extends Model
{
    use HasFactory;

    protected $casts = [
        'tasks' => 'array'
    ];
}
