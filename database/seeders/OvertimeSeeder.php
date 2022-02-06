<?php

namespace Database\Seeders;

use App\Models\Setting\OvertimeSetting;
use Illuminate\Database\Seeder;

class OvertimeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        OvertimeSetting::create([
            'rate_per_hour' => 50
        ]);
    }
}
