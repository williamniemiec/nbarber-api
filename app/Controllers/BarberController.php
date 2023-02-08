<?php

namespace App\Http\Controllers;

use App\Models\Barber;
use App\Models\BarberAvailability;
use App\Models\BarberPhoto;
use App\Models\BarberTestimonial;
use App\Models\Dto\BarberSearchDto;
use App\Models\UserAppointment;
use App\Models\UserFavorite;
use App\Services\BarberService;
use App\Utils\ParameterValidator;
use Illuminate\Http\Request;
use InvalidArgumentException;

class BarberController extends Controller
{
    private $loggedUser;
    private $barberService;

    public function __construct()
    {
        $this->middleware('auth:api');
        $this->loggedUser = auth()->user();
        $this->barberService = new BarberService();
    }

    public function list(Request $request)
    {
        $response = $this->barberService->findAll(
            BarberSearchDto::builder()
                ->latitude($request->input('latitude'))
                ->longitude($request->input('longitude'))
                ->city($request->input('city'))
                ->offset($request->input('offset'))
                ->limit($request->input('limit'))
                ->build()
        );

        return response()->json($response);
    }

    public function get(Request $request)
    {
        $id = $request->input('id');
        ParameterValidator::validateRequiredParameter($id, 'id');
        $barber = $this->barberService->findById($id);

        $response['data'] = $barber;

        $response['data']['services'] = \App\Models\BarberService::select(['id', 'name', 'price'])->where('id_barber', $id)->get();
        $response['data']['testimonials'] = BarberTestimonial::select(['id', 'title', 'rate', 'body', 'id_user'])->where('id_barber', $id)->get();


        // Fetches favorited
        $favorited = UserFavorite::where([
            ['id_user', '=', $this->loggedUser->id],
            ['id_barber', '=', $id]
        ])->count();
        $response['data']['favorited'] = ($favorited > 0);

        // Fetches photos
        $response['data']['photos'] = [];
        $photos = BarberPhoto::select(['id', 'url'])->where('id_barber', $id)->get();
        foreach ($photos as $key => $value) {
            $photos[$key]['url'] = url('media/uploads/'.$value);
        }
        if (!empty($photos)) {
            $response['data']['photos'] = $photos;
        }

        // Fetches availability
        $response['data']['availability'] = [];
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

    public function search(Request $request)
    {
        $q = $request->input('q');
        ParameterValidator::validateRequiredParameter($q, 'q');

        $response = $this->barberService->search($q);

        return response()->json($response);
    }
}
