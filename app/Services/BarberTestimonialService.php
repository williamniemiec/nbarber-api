<?php

namespace App\Services;

use App\Models\BarberTestimonial;
use App\Models\Dto\BarberTestimonialDto;

/**
 * Responsible for providing barber testimonial services.
 */
class BarberTestimonialService
{
    // ------------------------------------------------------------------------
    //         Methods
    // ------------------------------------------------------------------------
    public function findAllByBarberId($id): array
    {
        $testimonials = BarberTestimonial::select(['id', 'title', 'rate', 'body', 'user'])
            ->where('id_barber', $id)
            ->get()
            ->toArray();

        return array_map(
            fn($testimonial) => new BarberTestimonialDto($testimonial),
            $testimonials
        );
    }
}
