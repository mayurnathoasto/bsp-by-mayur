<?php

namespace Database\Seeders;

use App\Models\City;
use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
{
    public function run(): void
    {
        $cities = [
            ['country_code' => 'IN', 'name' => 'Mumbai', 'state' => 'Maharashtra', 'pincode' => '400001', 'is_active' => true],
            ['country_code' => 'IN', 'name' => 'Delhi', 'state' => 'Delhi', 'pincode' => '110001', 'is_active' => true],
            ['country_code' => 'IN', 'name' => 'Bengaluru', 'state' => 'Karnataka', 'pincode' => '560001', 'is_active' => true],
            ['country_code' => 'IN', 'name' => 'Hyderabad', 'state' => 'Telangana', 'pincode' => '500001', 'is_active' => true],
            ['country_code' => 'IN', 'name' => 'Chennai', 'state' => 'Tamil Nadu', 'pincode' => '600001', 'is_active' => true],
            ['country_code' => 'IN', 'name' => 'Kolkata', 'state' => 'West Bengal', 'pincode' => '700001', 'is_active' => true],
            ['country_code' => 'IN', 'name' => 'Pune', 'state' => 'Maharashtra', 'pincode' => '411001', 'is_active' => true],
            ['country_code' => 'IN', 'name' => 'Ahmedabad', 'state' => 'Gujarat', 'pincode' => '380001', 'is_active' => true],
            ['country_code' => 'IN', 'name' => 'Jaipur', 'state' => 'Rajasthan', 'pincode' => '302001', 'is_active' => true],
            ['country_code' => 'IN', 'name' => 'Indore', 'state' => 'Madhya Pradesh', 'pincode' => '452001', 'is_active' => true],

            // Optional: add your city (since you’ve used MP/Katni in other projects)
            ['country_code' => 'IN', 'name' => 'Katni', 'state' => 'Madhya Pradesh', 'pincode' => '483501', 'is_active' => true],
        ];

        foreach ($cities as $c) {
            City::updateOrCreate(
                ['country_code' => $c['country_code'], 'name' => $c['name'], 'state' => $c['state']],
                $c
            );
        }
    }
}
