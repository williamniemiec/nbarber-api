<?php
/*
 * Copyright (c) William Niemiec.
 *
 * This source code is licensed under the MIT license found in the
 * LICENSE file in the root directory of this source tree.
 */

namespace App\Services;

use App\Models\BarberPhoto;
use App\Dto\BarberPhotoDto;

/**
 * Responsible for providing barber photo services.
 */
class BarberPhotoService
{
    // ------------------------------------------------------------------------
    //         Methods
    // ------------------------------------------------------------------------
    public function findAllByBarberId(int $barberId): array
    {
        $photos = BarberPhoto::select(['id', 'url'])
            ->where('id_barber', $barberId)
            ->get()
            ->toArray();

        $photos = array_map(fn($photo) => new BarberPhoto($photo), $photos);

        return $this->toDto($photos);
    }

    private function toDto(array $photos): array
    {
        return array_map(fn($photo) => new BarberPhotoDto($photo), $photos);
    }
}
