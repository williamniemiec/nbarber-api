<?php

namespace App\Services;

use App\Models\Dto\UserAppointmentDto;
use App\Models\UserAppointment;

/**
 * Responsible for providing user appointment services.
 */
class UserAppointmentService
{
     // ------------------------------------------------------------------------
    //         Attributes
    // ------------------------------------------------------------------------
    private readonly BarberService $barberService;
    private readonly BarberServicesService $barberServicesService;


    // ------------------------------------------------------------------------
    //         Constructor
    // ------------------------------------------------------------------------
    public function __construct()
    {
        $this->barberService = new BarberService();
        $this->barberServicesService = new BarberServicesService();
    }


    // ------------------------------------------------------------------------
    //         Methods
    // ------------------------------------------------------------------------
    public function findAppointmentsByBarberIdLimit(int $id, int $limit): array
    {
        $appointments = UserAppointment::where('id_barber', $id)
            ->whereBetween('date', [
                date('Y-m-d').' 00:00:00',
                date('Y-m-d', strtotime('+'.$limit.' days')).' 23:59:59'
            ])
            ->get()
            ->toArray();

        return array_map(fn($appointment) => $appointment->date, $appointments);
    }

    public function findAppointmentsByUserId($userId): array
    {
        $appointments = [];
        $rawAppointments = UserAppointment::select()
            ->where('id_user', $userId)
            ->orderBy('date', 'DESC')
            ->get();

        if ($rawAppointments) {
            foreach ($rawAppointments as $appointment) {
                $appointments[] = UserAppointmentDto::builder()
                    ->id($appointment->id)
                    ->date($appointment->date)
                    ->barber($this->barberService->findById($appointment->id_barber))
                    ->service($this->barberServicesService->findById($appointment->id_service))
                    ->build();
            }
        }

        return $appointments;
    }
}
