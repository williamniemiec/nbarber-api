<?php

namespace App\Http\Controllers;

use App\Models\Barber;
use App\Models\BarberAvailability;
use App\Models\BarberPhoto;
use App\Models\BarberService;
use App\Models\BarberTestimonial;
use App\Models\UserAppointment;
use App\Models\UserFavorite;
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
        $offset = $request->input('offset');
        $limit = $request->input('limit');

        if (empty($offset)) {
            $offset = 0;
        }

        if (empty($limit)) {
            $limit = 5;
        }

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
            ->offset($offset)
            ->limit($limit)
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

    public function get(Request $request)
    {
        $response = ['error' => ''];
        $id = $request->id;
        $barber = Barber::find($id);

        if ($barber) {
            $response['data'] = $barber;
            $response['data']['avatar'] = url('media/avatars/' . $barber->avatar);
            $response['data']['photos'] = [];
            $response['data']['services'] = BarberService::select(['id', 'name', 'price'])->where('id_barber', $id)->get();
            $response['data']['testimonials'] = BarberTestimonial::select(['id', 'title', 'rate', 'body', 'id_user'])->where('id_barber', $id)->get();
            $response['data']['availability'] = [];

            // Fetches favorited
            $favorited = UserFavorite::where([
                ['id_user', '=', $this->loggedUser->id],
                ['id_barber', '=', $id]
            ])->count();
            $response['data']['favorited'] = ($favorited > 0);

            // Fetches photos
            $photos = BarberPhoto::select(['id', 'url'])->where('id_barber', $id)->get();
            foreach ($photos as $key => $value) {
                $photos[$key]['url'] = url('media/uploads/'.$value);
            }
            if (!empty($photos)) {
                $response['data']['photos'] = $photos;
            }

            // Fetches availability
            /// 1. Get work hours by weekday
            $workDays = BarberAvailability::select(['id', 'name', 'weekday', 'hours'])->where('id_barber', $id)->get();
            $workDaysByWeekday = [];
            foreach ($workDays as $workDay) {
                $workDaysByWeekday[$workDay->weekday] = explode(',', $workDay->hours);
            }

            /// 2. Get appointments
            $appointments = [];
            $appointmentsQuery = UserAppointment::where('id_barber', $id)
                ->whereBetween('date', [
                    date('Y-m-d').' 00:00:00',
                    date('Y-m-d', strtotime('+20 days')).' 23:59:59'
                ])
                ->get();
            foreach ($appointmentsQuery as $appointmentQuery) {
                $appointments[] = $appointmentQuery->date;
            }

            /// 3. Get available hours from now until 20 days after it.
            $availability = [];
            for ($day = 0; $day < 20; $day++) {
                $currentTime = strtotime('+' . $day . ' days');
                $currentWeekday = date('w', $currentTime);

                // if the barber has work hours in the current weekday
                if (in_array($currentWeekday, array_keys($workDaysByWeekday))) {
                    $availableHours = [];
                    $formattedDay = date('Y-m-d', $currentTime);

                    foreach ($workDaysByWeekday[$currentWeekday] as $hour) {
                        $formattedDateTime = $formattedDay . ' ' . $hour . ':00';

                        // if the barber does not have appointments in the current hour
                        if (!in_array($formattedDateTime, $appointments)) {
                            $availableHours[] = $hour;
                        }
                    }

                    if (!empty($availableHours)) {
                        $availability = [
                            'date' => $formattedDay,
                            'hours' => $availableHours
                        ];
                    }
                }
            }
            if (!empty($availability)) {
                $response['data']['availability'] = $availability;
            }


        }
        else {
            $response['error'] = 'Barber not found';
        }


        return response()->json($response);
    }

    public function insertAppointment($id, Request $request)
    {
        $array = ['error' => ''];
        $service = $request->input('service');
        $year = intval($request->input('year'));
        $month = intval($request->input('month'));
        $day = intval($request->input('day'));
        $hour = intval($request->input('hour'));

        $month = ($month < 10) ? '0' . $month : $month;
        $day = ($day < 10) ? '0' . $day : $day;
        $hour = ($hour < 10) ? '0' . $hour : $hour;

        // check if barber service exists
        $barberService = BarberService::select()
            ->where('id', $service)
            ->where('id_barber', $id)
            ->first();
        if (!$barberService) {
            return ['error' => 'This service does not exist'];
        }

        // check if date is valid
        $appointmentDate = $year . '-' . $month . '-' . $day . ' ' . $hour . ':00:00';
        if (!strtotime($appointmentDate)) {
            return ['error' => 'Invalid date'];
        }

        // check if barber has availability in the provided date
        $appointment = UserAppointment::select()
            ->where('id_barber', $id)
            ->where('date', $appointmentDate)
            ->count();
        if ($appointment > 0) {
            return ['error' => 'The barber has already an appointment in this date'];
        }

        // check if barber's workdays include the provided date
        $weekday = date('w', strtotime($appointmentDate));
        $availabilities = BarberAvailability::select()
            ->where('id_barber', $id)
            ->where('weekday', $weekday)
            ->first();
        if (!$availabilities) {
            return ['error' => 'The barber does not work on this day'];
        }

        // check if barber works at the requested hour
        $hours = explode(',', $availabilities->hours);
        if (!in_array($hour.':00', $hours)) {
            return ['error' => 'The barber does not work at this hour'];
        }

        // do appointment
        $newAppointment = new UserAppointment();
        $newAppointment->id_user = $this->loggedUser->id;
        $newAppointment->id_barber = $id;
        $newAppointment->id_service = $service;
        $newAppointment->date = $appointmentDate;
        $newAppointment->save();
    }
}
