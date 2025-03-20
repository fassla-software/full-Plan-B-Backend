<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class OperationCostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('operation_costs')->insert([
            'operation_type' => 1, // Make request
            'category_slug' => 'heavyEquipment',
            'cost' => 10,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('operation_costs')->insert([
            'operation_type' => 2, // Make offer
            'category_slug' => 'heavyEquipment',
            'cost' => 15,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('operation_costs')->insert([
            'operation_type' => 3, // update request
            'category_slug' => 'heavyEquipment',
            'cost' => 5,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('operation_costs')->insert([
            'operation_type' => 4, // update offer
            'category_slug' => 'heavyEquipment',
            'cost' => 5,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('operation_costs')->insert([
            'operation_type' => 5, // delete request
            'category_slug' => 'heavyEquipment',
            'cost' => 5,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('operation_costs')->insert([
            'operation_type' => 6, // delete offer
            'category_slug' => 'heavyEquipment',
            'cost' => 5,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('operation_costs')->insert([
            'operation_type' => 7, // make ads
            'category_slug' => 'heavyEquipment',
            'cost' => 20,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
