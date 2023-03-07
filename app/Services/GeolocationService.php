<?php
/*
 * Copyright (c) William Niemiec.
 *
 * This source code is licensed under the MIT license found in the
 * LICENSE file in the root directory of this source tree.
 */

namespace App\Services;

use App\Models\Dto\GeolocationDto;

/**
 * Responsible for providing addresses and latitude / longitude information..
 */
class GeolocationService
{
    // ------------------------------------------------------------------------
    //         Attributes
    // ------------------------------------------------------------------------
    private readonly string $apiKey;
    private readonly string $apiUrl;


    // ------------------------------------------------------------------------
    //         Constructor
    // ------------------------------------------------------------------------
    public function __construct()
    {
        $this->apiKey = env('GCLOUD_GEOCODING_KEY', null);
        $this->apiUrl = 'https://maps.googleapis.com/maps/api/geocode';
    }


    // ------------------------------------------------------------------------
    //         Methods
    // ------------------------------------------------------------------------
    public function getGeolocation(string $address): GeolocationDto
    {
        $geolocation = null;
        $url = $this->buildUrlForAddress($address);
        $response = $this->runCurl($url);

        if (count($response['results']) > 0) {
            $latitude = $response['results'][0]['geometry']['location']['lat'];
            $longitude = $response['results'][0]['geometry']['location']['lng'];
            $geolocation = new GeolocationDto($latitude, $longitude);
        }

        return $geolocation;
    }

    private function buildUrlForAddress($address): string
    {
        $url = $this->apiUrl.'/json';
        $url .= '?address=' . urlencode($address);
        $url .= '&key=' . $this->apiKey;

        return $url;
    }

    private function runCurl($url)
    {
        $channel = curl_init();
        curl_setopt($channel, CURLOPT_URL, $url);
        curl_setopt($channel, CURLOPT_RETURNTRANSFER, 1);

        $response = curl_exec($channel);

        curl_close($channel);

        return json_decode($response, true);
    }

    public function getAddress(float $latitude, float $longitude): string
    {
        $address = null;
        $url = $this->buildUrlForGeolocation($latitude, $longitude);
        $response = $this->runCurl($url);

        if (count($response['results']) > 0) {
            $address = $response['results'][0]['formatted_address'];
        }

        return $address;
    }

    private function buildUrlForGeolocation(float $latitude, float $longitude): string
    {
        $url = $this->apiUrl.'/json';
        $url .= '?address=' . urlencode($latitude.','.$longitude);
        $url .= '&key=' . $this->apiKey;

        return $url;
    }
}
