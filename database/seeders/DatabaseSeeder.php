<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\Availability;
use App\Models\Favorite;
use App\Models\Photo;
use App\Models\Review;
use App\Models\Service;
use App\Models\Testimonial;
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
        Appointment::factory(5)->create();
        Availability::factory(5)->create();
        Favorite::factory(3)->create();
        Photo::factory(6)->create();
        Review::factory(5)->create();
        Service::factory(7)->create();
        Testimonial::factory(5)->create();
    }
}
