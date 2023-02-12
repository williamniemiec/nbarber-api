<?php

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

        return array_map(fn($service) => new BarberServiceDto($service), $services);
    }
}
