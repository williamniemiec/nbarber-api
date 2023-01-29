<?php

namespace Database\Factories;

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
        $date = fake()->date();
        $results = UserAppointment::where([
            ['id_user', '=', $user->id],
            ['id_barber', '=', $barber->id],
            ['date', '=', $date],
        ]);

        while ($results->count() > 0) {
            $user = User::all()->random();
            $barber = Barber::all()->random();
            $date = fake()->date();
            $results = UserAppointment::where([
                ['id_user', '=', $user->id],
                ['id_barber', '=', $barber->id],
                ['date', '=', $date],
            ]);
        }

        return [
            'date' => $date,
            'id_user' => $user->id,
            'id_barber' => $barber->id
        ];
    }
}
