<?php

namespace Database\Factories;

use App\Models\BarberService;
use App\Models\UserAppointment;
use App\Models\Barber;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserAppointment>
 */
class UserAppointmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $user = User::all()->random();
        $barber = Barber::all()->random();
        $date = fake()->dateTimeThisMonth();
        $service = BarberService::all()->random();
        $results = UserAppointment::where([
            ['id_user', '=', $user->id],
            ['id_barber', '=', $barber->id],
            ['date', '=', $date],
            ['id_service', '=', $service->id]
        ]);

        while ($results->count() > 0) {
            $user = User::all()->random();
            $barber = Barber::all()->random();
            $date = fake()->date();
            $service = BarberService::all()->random();
            $results = UserAppointment::where([
                ['id_user', '=', $user->id],
                ['id_barber', '=', $barber->id],
                ['date', '=', $date],
                ['id_service', '=', $service->id]
            ]);
        }

        return [
            'date' => $date,
            'id_user' => $user->id,
            'id_barber' => $barber->id,
            'id_service' => $service->id
        ];
    }
}
