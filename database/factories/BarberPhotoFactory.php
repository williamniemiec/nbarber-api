<?php

namespace Database\Factories;

use App\Models\Barber;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BarberPhoto>
 */
class BarberPhotoFactory extends Factory
{
    private static $barberPhotos = [
        "https://nationaltoday.com/wp-content/uploads/2022/02/Barbers-Day.jpg",
        "https://i2-prod.manchestereveningnews.co.uk/incoming/article21411590.ece/ALTERNATES/s615/0_gettyimages-1207048163-170667a.jpg",
        "https://images.unsplash.com/photo-1599351431202-1e0f0137899a?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxzZWFyY2h8Mnx8YmFyYmVyJTIwc2hvcHxlbnwwfHwwfHw%3D&w=1000&q=80",
        "https://blog.mensmarket.com.br/wp-content/uploads/2018/01/barbearia-barber-shop.jpg"
    ];

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $barber = Barber::all()->random();
        $totalImages = count(BarberPhotoFactory::$barberPhotos);

        return [
            'id_barber' => $barber->id,
            'url' => BarberPhotoFactory::$barberPhotos[fake()->numberBetween(0, $totalImages-1)]
        ];
    }
}
