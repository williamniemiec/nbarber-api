<?php

namespace Database\Factories;

use App\Models\Barber;
use App\Models\BarberService;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BarberService>
 */
class BarberServiceFactory extends Factory
{
    private $services = [
        'Classic haircut',
        'Fade haircut',
        'Straight razor shave',
        'Kid\'s haircut'
    ];

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $barber = Barber::all()->random();
        $randomIndex = fake()->numberBetween(0, count($this->services) - 1);
        $results = BarberService::where([
            ['name', '=', $this->services[$randomIndex]],
            ['id_barber', '=', $barber->id],
        ]);

        while ($results->count() > 0) {
            $barber = Barber::all()->random();
            $randomIndex = fake()->numberBetween(0, count($this->services) - 1);
            $results = BarberService::where([
                ['name', '=', $this->services[$randomIndex]],
                ['id_barber', '=', $barber->id],
            ]);
        }

        return [
            'name' => $this->services[$randomIndex],
            'id_barber' => $barber->id,
            'price' => fake()->randomFloat(2, 5, 35)
        ];
    }
}
