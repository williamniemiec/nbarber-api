<?php

namespace App\Http\Controllers;

use App\Models\Barber;
use Illuminate\Http\Request;

class BarberController extends Controller
{
    private $loggedUser;

    public function __construct()
    {
        $this->middleware('auth:api');
        $this->loggedUser = auth()->user();
    }

    public function list(Request $request)
    {
        $array = ['error' => ''];
        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');
        $city = $request->input('city');

        if (!empty($city)) {
            $response = $this->searchGeo($city);

            if (count($response['results']) > 0) {
                $latitude = $response['results'][0]['geometry']['location']['lat'];
                $longitude = $response['results'][0]['geometry']['location']['lng'];
            }
        }
        else if (!empty($latitude) && !empty($longitude)) {
            $response = $this->searchGeo($latitude.','.$longitude);

            if (count($response['results']) > 0) {
                $city = $response['results'][0]['formatted_address'];
            }
        }
        else {
            $latitude = '-30.0097565';
            $longitude = '-51.1463625';
            $city = 'Porto Alegre';
        }

        $array['data'] = Barber::select(
            Barber::raw('*, SQRT(
                POW(69.1 * (latitude - '.$latitude.'), 2) +
                POW(69.1 * ('.$longitude.' - longitude) * COS(latitude / 57.3), 2)) as distance')
            )
            ->orderBy('distance', 'ASC')
            ->get();

        $array['loc'] = $city;

        foreach ($array['data'] as $key => $value) {
            $array['data'][$key]['avatar'] = url('media/avatars/' . $array['data'][$key]['avatar']);
        }

        return response()->json($array);
    }

    private function searchGeo($address)
    {
        $key = env('GCLOUD_GEOCODING_KEY', null);
        $url = 'https://maps.googleapis.com/maps/api/geocode/json?address=' . urlencode($address);
        $url .= '&key=' . $key;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $response = curl_exec($ch);

        curl_close($ch);

        return json_decode($response, true);
    }
}
