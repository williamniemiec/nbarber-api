<?php

namespace Database\Factories;

use App\Models\Barber;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BarberReview>
 */
class BarberReviewFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $barber = Barber::all()->random();

        return [
            'id_barber' => $barber->id,
            'rate' => fake()->randomFloat(2, 3, 5)
        ];
    }
}
