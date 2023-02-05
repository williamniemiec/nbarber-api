<?php

namespace App\Services;

use App\Models\Dto\BarberSearchDto;

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
    public function findAll(BarberSearchDto $barberSearch)
    {
        $response = ['error' => ''];
        $latitude = $barberSearch->getLatitude();
        $longitude = $barberSearch->getLongitude();
        $city = $barberSearch->getCity();

        if (!empty($city)) {
            $geolocation = $this->geolocationService->getGeolocation($city);

            if (!$geolocation) {
                $response['error'] = 'Address not found';
            }
            $latitude = $geolocation->getLatitude();
            $longitude = $geolocation->getLongitude();
        }
        else if (!empty($latitude) && !empty($longitude)) {
            $city = $this->geolocationService->getAddress($latitude, $longitude);

            if (!$city) {
                $response['error'] = 'Latitude and longitude not found';
            }
        }
        else {
            $latitude = '-30.0097565';
            $longitude = '-51.1463625';
            $city = 'Porto Alegre';
        }

        $response['data'] = $this->findBarbersByLocation(
            $latitude,
            $longitude,
            $barberSearch->getOffset(),
            $barberSearch->getLimit()
        );
        $response['loc'] = $city;

        return $response;
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
            ->get();

        foreach ($barbers as $key => $value) {
            $barbers[$key]['avatar'] = url('media/avatars/' . $barbers[$key]['avatar']);
        }

        return $barbers;
    }
}
