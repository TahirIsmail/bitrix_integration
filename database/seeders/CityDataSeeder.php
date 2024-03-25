<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class CityDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $cities = [
            'Lahore' => 'Lahore',
            'Karachi' => 'Karachi',
            'Islamabad-Rawalpindi' => 'Islamabad-Rawalpindi',
            'Faisalabad' => 'Faisalabad',
            'Multan' => 'Multan'
        ];
        
        $cityIds = [];
        foreach ($cities as $city) {
            $cityIds[$city] = DB::table('incubator_cities')->insertGetId(['name' => $city]);
        }
        
        // Define shifts, timings, and charges
        $shifts = ['Night', 'Morning', 'Evening'];
        $timings = ['12AM - 8AM', '8AM - 4PM', '4PM - 12AM'];
        $charges = [25000.00, 25000.00, 25000.00];
        
        // Insert shifts, timings, and charges for each city
        foreach ($cityIds as $city => $cityId) {
            for ($i = 0; $i < count($shifts); $i++) {
                $shift = DB::table('shifts')->insertGetId(['incubator_city_id' => $cityId, 'name' => $shifts[$i]]);
                $timing = DB::table('incubator_timings_tbl')->insertGetId(['shift_id' => $shift, 'name' => $timings[$i]]);
                DB::table('incubator_charges_tbl')->insert(['incubator_timings_id' => $timing, 'amount' => $charges[$i]]);
            }
        }
    
        
    }
}
