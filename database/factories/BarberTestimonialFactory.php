<?php

namespace Database\Factories;

use App\Models\Barber;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BarberTestimonial>
 */
class BarberTestimonialFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $barber = Barber::all()->random();
        $user = User::all()->random();

        return [
            'title' => fake()->title(),
            'rate' => fake()->randomFloat(2, 3, 5),
            'body' => fake()->text(),
            'id_barber' => $barber->id,
            'id_user' => $user->id
        ];
    }
}
