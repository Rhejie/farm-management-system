<?php

namespace Database\Seeders;

use App\Models\Setting\DeductionSetting;
use Illuminate\Database\Seeder;

class DeductionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $deductions = [
            ['name' => 'Philhealth', 'rate' => 4.5],
            ['name' => 'SSS', 'rate' => 3]
        ];
        foreach ($deductions as $deduction) {
            DeductionSetting::create($deduction);
        }
    }
}
