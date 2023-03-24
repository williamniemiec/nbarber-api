<?php
/*
 * Copyright (c) William Niemiec.
 *
 * This source code is licensed under the MIT license found in the
 * LICENSE file in the root directory of this source tree.
 */

namespace App\Services;

use App\Exceptions\ObjectNotFoundException;
use App\Models\Barber;
use App\Models\Dto\BarberSearchDto;
use App\Models\Dto\BarberSearchResultDto;
use InvalidArgumentException;

/**
 * Responsible for providing barber services.
 */
class BarberService
{
    // ------------------------------------------------------------------------
    //         Attributes
    // ------------------------------------------------------------------------
    private readonly GeolocationService $geolocationService;


    // ------------------------------------------------------------------------
    //         Constructor
    // ------------------------------------------------------------------------
    public function __construct()
    {
        $this->geolocationService = new GeolocationService();
    }


    // ------------------------------------------------------------------------
    //         Methods
    // ------------------------------------------------------------------------
    public function findAll(BarberSearchDto $barberSearch): BarberSearchResultDto
    {
        $latitude = $barberSearch->getLatitude();
        $longitude = $barberSearch->getLongitude();
        $city = $barberSearch->getCity();

        if (!empty($city)) {
            $geolocation = $this->geolocationService->getGeolocation($city);

            if (!$geolocation) {
                throw new InvalidArgumentException('Address not found');
            }
            $latitude = $geolocation->getLatitude();
            $longitude = $geolocation->getLongitude();
        }
        else if (!empty($latitude) && !empty($longitude)) {
            $city = $this->geolocationService->getAddress($latitude, $longitude);

            if (!$city) {
                throw new InvalidArgumentException('Invalid latitude and / or longitude');
            }
        }
        else {
            $latitude = '-30.0097565';
            $longitude = '-51.1463625';
            $city = 'Porto Alegre';
        }

        $barbers = $this->findBarbersByLocation(
            $latitude,
            $longitude,
            $barberSearch->getOffset(),
            $barberSearch->getLimit()
        );

        return new BarberSearchResultDto($barbers, $city);
    }

    private function findBarbersByLocation($latitude, $longitude, $offset, $limit)
    {
        $barbers = Barber::select(
                Barber::raw('*, SQRT(
                    POW(69.1 * (latitude - '.$latitude.'), 2) +
                    POW(69.1 * ('.$longitude.' - longitude) * COS(latitude / 57.3), 2)) as distance'
                )
            )
            ->orderBy('distance', 'ASC')
            ->offset(empty($offset) ? 0 : $offset)
            ->limit(empty($limit) ? 5 : $limit)
            ->get()
            ->toArray();

        $barbers = array_map(fn($barber) => new Barber($barber), $barbers);
        $this->completeAvatarsUrl($barbers);

        return $barbers;
    }

    private function completeAvatarsUrl(array $barbers): void
    {
        foreach ($barbers as $barber) {
            $this->completeAvatarUrl($barber);
        }
    }

    private function completeAvatarUrl(Barber $barber): void
    {
        $barber->avatar = url('media/avatars/' . $barber->avatar);
    }

    public function search(string $term): array
    {
        $barbers = Barber::select()
            ->where('name', 'LIKE', '%' . $term . '%')
            ->get()
            ->toArray();

        $barbers = array_map(fn($barber) => new Barber($barber), $barbers);
        $this->completeAvatarsUrl($barbers);

        return $barbers;
    }

    public function findById($id): Barber
    {
        $barber = Barber::find($id);

        if (!$barber) {
            throw new ObjectNotFoundException($id);
        }

        $this->completeAvatarUrl($barber);

        return $barber;
    }
}
