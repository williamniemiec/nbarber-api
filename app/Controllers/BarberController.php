<?php

namespace App\Http\Controllers;

use App\Models\Dto\BarberDto;
use App\Models\Dto\BarberSearchDto;
use App\Models\UserAppointment;
use App\Services\AuthService;
use App\Services\BarberAvailabilityService;
use App\Services\BarberPhotoService;
use App\Services\BarberService;
use App\Services\BarberServicesService;
use App\Services\BarberTestimonialService;
use App\Services\UserService;
use App\Utils\ParameterValidator;
use Illuminate\Http\Request;

class BarberController extends Controller
{
    // ------------------------------------------------------------------------
    //         Attributes
    // ------------------------------------------------------------------------
    private readonly AuthService $authService;
    private readonly BarberService $barberService;
    private readonly BarberPhotoService $barberPhotoService;
    private readonly BarberAvailabilityService $availabilityService;
    private readonly BarberServicesService $barberServicesService;
    private readonly BarberTestimonialService $testimonialService;
    private readonly UserService $userService;


    // ------------------------------------------------------------------------
    //         Constructor
    // ------------------------------------------------------------------------
    public function __construct()
    {
        $this->middleware('auth:api');
        $this->authService = new AuthService();
        $this->barberService = new BarberService();
        $this->barberPhotoService = new BarberPhotoService();
        $this->availabilityService = new BarberAvailabilityService();
        $this->barberServicesService = new BarberServicesService();
        $this->testimonialService = new BarberTestimonialService();
        $this->userService = new UserService();
    }


    // ------------------------------------------------------------------------
    //         Methods
    // ------------------------------------------------------------------------
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
        $authenticatedUser = $this->authService->getAuthenticatedUser();

        $barber = BarberDto::builder()
            ->barber($this->barberService->findById($id))
            ->services($this->barberServicesService->findAllByBarberId($id))
            ->testimonials($this->testimonialService->findAllByBarberId($id))
            ->favorited($this->userService->hasFavorited($authenticatedUser->id, $id))
            ->photos($this->barberPhotoService->findAllByBarberId($id))
            ->availability($this->availabilityService->findAvailability($id))
            ->build();

        return response()->json($barber);
    }

    public function insertAppointment($id, Request $request)
    {
        $service = $request->input('service');
        $year = intval($request->input('year'));
        $month = intval($request->input('month'));
        $day = intval($request->input('day'));
        $hour = intval($request->input('hour'));
        $appointmentDate = $this->buildAppointmentDate($day, $month, $year, $hour);

        $this->validateServiceExists($service, $id);
        $this->validateDate($appointmentDate);
        $this->validateBarberHasAvailability($appointmentDate, $id);
        $this->validateBarberWorksOnProvidedDate($appointmentDate, $hour, $id);

        $newAppointment = new UserAppointment();
        $newAppointment->id_user = $this->authService->getAuthenticatedUser()->id;
        $newAppointment->id_barber = $id;
        $newAppointment->id_service = $service;
        $newAppointment->date = $appointmentDate;
        $newAppointment->save();
    }

    private function buildAppointmentDate($day, $month, $year, $hour)
    {
        $month = ($month < 10) ? '0' . $month : $month;
        $day = ($day < 10) ? '0' . $day : $day;
        $hour = ($hour < 10) ? '0' . $hour : $hour;

        return $year . '-' . $month . '-' . $day . ' ' . $hour . ':00:00';
    }

    private function validateDate($date)
    {
        if (!strtotime($date)) {
            return response()->json(['error' => 'Invalid date']);
        }
    }

    private function validateServiceExists($service, $barberId)
    {
        if (!$this->barberServicesService->hasService($service, $barberId)) {
            return response()->json(['error' => 'This service does not exist']);
        }
    }

    private function validateBarberHasAvailability($date, $barberId)
    {
        $appointment = UserAppointment::select()
            ->where('id_barber', $barberId)
            ->where('date', $date)
            ->count();

        if ($appointment > 0) {
            return ['error' => 'The barber has already an appointment in this date'];
        }
    }

    private function validateBarberWorksOnProvidedDate($date, $hour, $barberId)
    {
        $weekday = date('w', strtotime($date));

        if (!$this->availabilityService->hasAvailabilityOnWeekday($weekday, $barberId)) {
            return ['error' => 'The barber does not work on this day'];
        }

        if (!$this->availabilityService->hasAvailabilityOnWeekdayAtHour($weekday, $hour, $barberId)) {
            return ['error' => 'The barber does not work at this hour'];
        }
    }

    public function search(Request $request)
    {
        $q = $request->input('q');
        ParameterValidator::validateRequiredParameter($q, 'q');

        $response = $this->barberService->search($q);

        return response()->json($response);
    }
}
