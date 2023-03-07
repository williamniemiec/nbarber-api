<?php
/*
 * Copyright (c) William Niemiec.
 *
 * This source code is licensed under the MIT license found in the
 * LICENSE file in the root directory of this source tree.
 */

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
