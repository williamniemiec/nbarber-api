<?php

namespace App\Http\Controllers;

use App\Models\BarberAvailability;
use App\Models\BarberPhoto;
use App\Models\BarberTestimonial;
use App\Models\Dto\BarberSearchDto;
use App\Models\UserAppointment;
use App\Models\UserFavorite;
use App\Services\BarberAvailabilityService;
use App\Services\BarberPhotoService;
use App\Services\BarberService;
use App\Services\BarberServicesService;
use App\Services\UserService;
use App\Utils\ParameterValidator;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Request;

class BarberController extends Controller
{
    private readonly Authenticatable $loggedUser;
    private readonly BarberService $barberService;
    private readonly BarberPhotoService $barberPhotoService;
    private readonly BarberAvailabilityService $availabilityService;
    private readonly BarberServicesService $barberServicesService;
    private readonly UserService $userService;

    public function __construct()
    {
        $this->middleware('auth:api');
        $this->loggedUser = auth()->user();
        $this->barberService = new BarberService();
        $this->barberPhotoService = new BarberPhotoService();
        $this->availabilityService = new BarberAvailabilityService();
        $this->barberServicesService = new BarberServicesService();
        $this->userService = new UserService();
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

        $response['data'] = $this->barberService->findById($id);
        $response['data']['services'] = $this->barberServicesService->findAllByBarberId($id);
        $response['data']['testimonials'] = BarberTestimonial::select(['id', 'title', 'rate', 'body', 'id_user'])->where('id_barber', $id)->get();
        $response['data']['favorited'] = $this->userService->hasFavorited($id);
        $response['data']['photos'] = $this->barberPhotoService->findAllByBarberId($id);
        $response['data']['availability'] = $this->availabilityService->findAvailability($id);

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
