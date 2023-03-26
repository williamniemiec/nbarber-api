<?php
/*
 * Copyright (c) William Niemiec.
 *
 * This source code is licensed under the MIT license found in the
 * LICENSE file in the root directory of this source tree.
 */

namespace App\Services;

use App\Models\BarberService;
use App\Models\Dto\BarberServiceDto;

/**
 * Responsible for providing services related to barber services.
 */
class BarberServicesService
{
    // ------------------------------------------------------------------------
    //         Methods
    // ------------------------------------------------------------------------
    public function findAllByBarberId($id): array
    {
        $services = BarberService::select(['id', 'name', 'price'])
            ->where('id_barber', $id)
            ->get()
            ->toArray();

        return array_map(fn($service) => new BarberServiceDto(new BarberService($service)), $services);
    }

    public function hasService($serviceId, $barberId): bool
    {
        $service = BarberService::select()
            ->where('id', $serviceId)
            ->where('id_barber', $barberId)
            ->first();

        return ($service != null);
    }

    public function findById($id)
    {
        $service = BarberService::select()
            ->where('id', $id)
            ->first();

        return new BarberServiceDto($service);
    }
}
