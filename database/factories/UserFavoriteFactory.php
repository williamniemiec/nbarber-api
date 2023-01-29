<?php

namespace Database\Factories;

use App\Models\Barber;
use App\Models\UserFavorite;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Foundation\Auth\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserFavorite>
 */
class UserFavoriteFactory extends Factory
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
        $results = UserFavorite::where([
            ['id_barber', '=', $barber->id],
            ['id_user', '=', $user->id],
        ]);

        while ($results->count() > 0) {
            $user = User::all()->random();
            $barber = Barber::all()->random();
            $results = UserFavorite::where([
                ['id_barber', '=', $barber->id],
                ['id_user', '=', $user->id],
            ]);
        }

        return [
            'id_barber' => $barber->id,
            'id_user' => $user->id
        ];
    }
}
