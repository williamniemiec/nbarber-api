<?php

namespace App\Services;

use App\Models\UserAppointment;

/**
 * Responsible for providing user appointment services.
 */
class UserAppointmentService
{
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
}
