<?php

namespace Database\Seeders;

use App\Models\Zone;
use Illuminate\Database\Seeder;

class ZoneSeeder extends Seeder
{
    /**
     * Seed the application's database with 4-hour radius zones around San Jose, CA.
     */
    public function run(): void
    {
        $zones = [
            // --- REGION 1: BAY AREA & SILICON VALLEY ---
            ['name' => 'San Jose / South Bay', 'state' => 'CA', 'is_active' => true],
            ['name' => 'Santa Clara / Sunnyvale / Cupertino', 'state' => 'CA', 'is_active' => true],
            ['name' => 'Palo Alto / Mountain View', 'state' => 'CA', 'is_active' => true],
            ['name' => 'Morgan Hill / Gilroy', 'state' => 'CA', 'is_active' => true],
            ['name' => 'San Mateo / Peninsula', 'state' => 'CA', 'is_active' => true],
            ['name' => 'San Francisco', 'state' => 'CA', 'is_active' => true],
            ['name' => 'Oakland / Alameda / Berkeley', 'state' => 'CA', 'is_active' => true],
            ['name' => 'Hayward / Fremont / Union City', 'state' => 'CA', 'is_active' => true],
            ['name' => 'Tri-Valley (Dublin / Pleasanton / Livermore)', 'state' => 'CA', 'is_active' => true],
            ['name' => 'Contra Costa (Concord / Walnut Creek / Pittsburg)', 'state' => 'CA', 'is_active' => true],
            ['name' => 'Solano (Vallejo / Fairfield / Vacaville)', 'state' => 'CA', 'is_active' => true],
            ['name' => 'Marin County (San Rafael / Novato)', 'state' => 'CA', 'is_active' => true],

            // --- REGION 2: CENTRAL COAST & MONTEREY BAY ---
            ['name' => 'Santa Cruz / Watsonville', 'state' => 'CA', 'is_active' => true],
            ['name' => 'Monterey / Seaside / Marina', 'state' => 'CA', 'is_active' => true],
            ['name' => 'Salinas / Hollister', 'state' => 'CA', 'is_active' => true],
            ['name' => 'South Salinas Valley (Soledad / Greenfield / King City)', 'state' => 'CA', 'is_active' => true],
            ['name' => 'Paso Robles / Atascadero', 'state' => 'CA', 'is_active' => true],
            ['name' => 'San Luis Obispo / Pismo Beach', 'state' => 'CA', 'is_active' => true],

            // --- REGION 3: CENTRAL VALLEY ---
            ['name' => 'Tracy / Mountain House', 'state' => 'CA', 'is_active' => true],
            ['name' => 'Stockton / Lodi / Manteca', 'state' => 'CA', 'is_active' => true],
            ['name' => 'Modesto / Turlock / Ceres', 'state' => 'CA', 'is_active' => true],
            ['name' => 'Merced / Los Banos / Atwater', 'state' => 'CA', 'is_active' => true],
            ['name' => 'Madera / Chowchilla', 'state' => 'CA', 'is_active' => true],
            ['name' => 'Fresno / Clovis / Sanger', 'state' => 'CA', 'is_active' => true],
            ['name' => 'South Fresno Co. (Selma / Kingsburg / Coalinga)', 'state' => 'CA', 'is_active' => true],

            // --- REGION 4: SACRAMENTO METRO AREA ---
            ['name' => 'Sacramento Metro / West Sacramento', 'state' => 'CA', 'is_active' => true],
            ['name' => 'Elk Grove / Rancho Cordova', 'state' => 'CA', 'is_active' => true],
            ['name' => 'Roseville / Rocklin / Lincoln', 'state' => 'CA', 'is_active' => true],
            ['name' => 'Yolo (Davis / Woodland)', 'state' => 'CA', 'is_active' => true],

            // --- REGION 5: WINE COUNTRY & NORTH COAST ---
            ['name' => 'Napa Valley (Napa / St. Helena / Calistoga)', 'state' => 'CA', 'is_active' => true],
            ['name' => 'Sonoma County (Santa Rosa / Petaluma / Healdsburg)', 'state' => 'CA', 'is_active' => true],
            ['name' => 'Ukiah / Lakeport / Clearlake', 'state' => 'CA', 'is_active' => true],

            // --- REGION 6: GOLD COUNTRY & FOOTHILLS ---
            ['name' => 'Placer / El Dorado Foothills (Auburn / Placerville)', 'state' => 'CA', 'is_active' => true],
            ['name' => 'Gold Country (Jackson / Sonora / Angels Camp)', 'state' => 'CA', 'is_active' => true],
        ];

        foreach ($zones as $zone) {
            Zone::updateOrCreate(
                ['name' => $zone['name'], 'state' => $zone['state']],
                ['is_active' => $zone['is_active']]
            );
        }
    }
}