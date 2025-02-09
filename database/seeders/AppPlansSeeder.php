<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AppPlansSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('apps_plans')->insert([
            [
                'apps_id' => '1',
                'name' => 'monthly-10-1',
                //'payment_interval' => 'monthly',
                'description' => 'monthly-10-1',
                'stripe_id' => 'monthly-10-1',
                'stripe_plan' => 'monthly-10-1',
                'pricing' => 10,
                'pricing_anual' => 100,
                'currency_id' => 1,
                'free_trial_dates' => 14,
                'is_default' => 1,
                'payment_frequencies_id' => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ], [
                'apps_id' => '1',
                'name' => 'monthly-10-2',
                // 'payment_interval' => 'monthly',
                'description' => 'monthly-10-2',
                'stripe_id' => 'monthly-10-2',
                'stripe_plan' => 'monthly-10-2',
                'pricing' => 100,
                'pricing_anual' => 100,
                'currency_id' => 1,
                'free_trial_dates' => 14,
                'is_default' => 0,
                'payment_frequencies_id' => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'apps_id' => '1',
                'name' => 'yearly-10-1',
                //  'payment_interval' => 'yearly',
                'description' => 'yearly-10-1',
                'stripe_id' => 'yearly-10-1',
                'stripe_plan' => 'yearly-10-1',
                'pricing' => 100,
                'pricing_anual' => 60,
                'currency_id' => 1,
                'free_trial_dates' => 14,
                'is_default' => 1,
                'payment_frequencies_id' => 2,
                'created_at' => date('Y-m-d H:i:s'),
            ], [
                'apps_id' => '1',
                'name' => 'yearly-10-2',
                //  'payment_interval' => 'yearly',
                'description' => 'yearly-10-2',
                'stripe_id' => 'yearly-10-2',
                'stripe_plan' => 'yearly-10-2',
                'pricing' => 1000,
                'pricing_anual' => 60,
                'currency_id' => 1,
                'free_trial_dates' => 14,
                'is_default' => 0,
                'payment_frequencies_id' => 2,
                'created_at' => date('Y-m-d H:i:s'),
            ]
        ]);
    }
}
