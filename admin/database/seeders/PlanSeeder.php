<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    public function run(): void
    {
        $plans = [
            [
                'name' => 'Free',
                'rate' => 0,
                'duration_days' => 15,
            ],
            [
                'name' => 'Gold',
                'rate' => 3000,
                'duration_days' => 180,
            ],
            [
                'name' => 'Platinum',
                'rate' => 4800,
                'duration_days' => 365,
            ],
        ];

        foreach ($plans as $plan) {
            Plan::updateOrCreate(
                ['name' => $plan['name']],
                $plan
            );
        }
    }
}
