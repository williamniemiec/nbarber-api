<?php

namespace Database\Factories;

use App\Models\Barber;
use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Service>
 */
class ServiceFactory extends Factory
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
        $results = Service::where([
            ['name', '=', $this->services[$randomIndex]],
            ['id_barber', '=', $barber->id],
        ]);

        while (count($results) > 0) {
            $barber = Barber::all()->random();
            $randomIndex = fake()->numberBetween(0, count($this->services) - 1);
            $results = Service::where([
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
