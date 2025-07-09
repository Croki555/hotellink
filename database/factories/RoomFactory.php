<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Room>
 */
class RoomFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'room_number' => $this->faker->unique()->numberBetween(100, 150),
            'price_for_night' => $this->faker->numberBetween(1000, 2500),
            'price_for_day' => $this->faker->numberBetween(500, 750),
            'status_id' => 2
        ];
    }
}
