<?php

use App\Setting;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class SeetingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        DB::table('settings')->delete();

        $settings = [
            [
                'store_name' => "John Doe's restaurant",
                'business_name' => 'John DoeldlyDoe',
                'phone' => '+31 687961984',
                'deliver_cost' => '1.50',
                'address' => 'Hofplein 2151, 3012 CM Rotterdam, Netherlands',
                'latitude' => '51.9244',
                'longitude' => '4.47773',
                'home_header' => 'default.png',
                'info_header' => 'default.png',
                'open_time' => '10:00',
                'close_time' => '22:30',
                'is_open_sat' => false,
                'is_open_sun' => false,
                'description' =>"Keep in mind!\nWe do not deliver to customers who do not provide any details"
            ],
        ];

        foreach ($settings as $setting) {
            Setting::create($setting);
        }
        Model::reguard();
    }
}
