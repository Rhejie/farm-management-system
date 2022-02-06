<?php

namespace Database\Seeders;

use App\Models\Setting\FinanceSetting;
use Illuminate\Database\Seeder;

class FinanceSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        FinanceSetting::create([
            'philhealth' => 4.5,
            'sss' => 3,
            'overtime_rate_hour' => 50,
        ]);
    }
}
