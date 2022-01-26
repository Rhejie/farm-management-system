<?php

namespace App\Models\Warehouse;

use App\Models\HR\Area;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deploy extends Model
{
    use HasFactory;

    public function stock() {

        return $this->belongsTo(Stock::class, 'stock_id', 'id');

    }

    public function item() {

        return $this->belongsTo(Item::class, 'item_id', 'id');

    }

    public function area() {

        return $this->belongsTo(Area::class, 'area_id', 'id');

    }
}
