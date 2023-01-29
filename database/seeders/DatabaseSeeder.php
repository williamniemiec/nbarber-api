<?php

namespace Database\Seeders;

use App\Models\UserAppointment;
use App\Models\BarberAvailability;
use App\Models\Barber;
use App\Models\UserFavorite;
use App\Models\BarberPhoto;
use App\Models\BarberReview;
use App\Models\BarberService;
use App\Models\BarberTestimonial;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            UserSeeder::class
        ]);

        Barber::factory(5)->create();
        UserAppointment::factory(5)->create();
        BarberAvailability::factory(5)->create();
        UserFavorite::factory(3)->create();
        BarberPhoto::factory(6)->create();
        BarberReview::factory(5)->create();
        BarberService::factory(7)->create();
        BarberTestimonial::factory(5)->create();
    }
}
