<?php

namespace Database\Factories;

use App\Models\BarberAvailability;
use App\Models\Barber;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BarberAvailability>
 */
class BarberAvailabilityFactory extends Factory
{
    private $services = [
        'Classic haircut',
        'Fade haircut',
        'Straight razor shave',
        'Kid\'s haircut'
    ];
    private $hours = [
        '07:00', '07:30', '08:00', '08:30', '09:00', '09:30', '10:00'
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
        $weekday = fake()->numberBetween(0, 6);
        $hours = implode(',', $this->hours);
        $results = BarberAvailability::where([
            ['name', '=', $this->services[$randomIndex]],
            ['id_barber', '=', $barber->id],
            ['weekday', '=', $weekday],
            ['hours', '=', $hours],
        ]);

        while ($results->count() > 0) {
            $barber = Barber::all()->random();
            $randomIndex = fake()->numberBetween(0, count($this->services) - 1);
            $weekday = fake()->numberBetween(0, 6);

            $results = BarberAvailability::where([
                ['name', '=', $this->services[$randomIndex]],
                ['weekday', '=', $weekday],
                ['hours', '=', $hours],
                ['id_barber', '=', $barber->id],
            ]);
        }

        return [
            'name' => $this->services[$randomIndex],
            'weekday' => $weekday,
            'hours' => $hours,
            'id_barber' => $barber->id,
        ];
    }
}
