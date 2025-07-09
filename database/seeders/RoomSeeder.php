<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as FakerFactory;
class RoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = FakerFactory::create();

        DB::table('rooms')->insert([
           [
               'room_number' => 111,
               'price_for_night' => $faker->numberBetween(1000, 2500),
               'price_for_day' => $faker->numberBetween(500, 750),
               'status_id' => 2
           ],
            [
                'room_number' => 112,
                'price_for_night' => $faker->numberBetween(1000, 2500),
                'price_for_day' => $faker->numberBetween(500, 750),
                'status_id' => 2
            ],
            [
                'room_number' => 113,
                'price_for_night' => $faker->numberBetween(1000, 2500),
                'price_for_day' => $faker->numberBetween(500, 750),
                'status_id' => 2
            ],
            [
                'room_number' => 114,
                'price_for_night' => $faker->numberBetween(1000, 2500),
                'price_for_day' => $faker->numberBetween(500, 750),
                'status_id' => 2
            ],
            [
                'room_number' => 115,
                'price_for_night' => $faker->numberBetween(1000, 2500),
                'price_for_day' => $faker->numberBetween(500, 750),
                'status_id' => 2
            ],
        ]);
    }
}
