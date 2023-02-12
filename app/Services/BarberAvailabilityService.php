<?php

namespace App\Services;

use App\Models\BarberAvailability;
use App\Models\Dto\BarberAvailabilityDto;

/**
 * Responsible for providing barber availability services.
 */
class BarberAvailabilityService
{
    // ------------------------------------------------------------------------
    //         Attributes
    // ------------------------------------------------------------------------
    private const LIMIT = 20;
    private readonly UserAppointmentService $appointmentService;


    // ------------------------------------------------------------------------
    //         Constructor
    // ------------------------------------------------------------------------
    public function __construct()
    {
        $this->appointmentService = new UserAppointmentService();
    }


    // ------------------------------------------------------------------------
    //         Methods
    // ------------------------------------------------------------------------
    public function findAvailability(int $barberId): array
    {
        $availability = [];
        $workDaysByWeekday = $this->findWorkdaysGroupByWeekday($barberId);
        $appointments = $this->appointmentService->findAppointmentsByBarberIdLimit(
            $barberId,
            BarberAvailabilityService::LIMIT
        );

        for ($day = 0; $day < BarberAvailabilityService::LIMIT; $day++) {
            $availableHours = $this->findAvailabilityForDay(
                $day,
                $workDaysByWeekday,
                $appointments
            );

            if ($availableHours != null) {
                $availability[] = $availableHours;
            }
        }

        return $availability;
    }

    private function findWorkdaysGroupByWeekday($barberId): array
    {
        $workDaysByWeekday = [];
        $workDays = BarberAvailability::select(['id', 'name', 'weekday', 'hours'])
            ->where('id_barber', $barberId)
            ->get();

        foreach ($workDays as $workDay) {
            $workDaysByWeekday[$workDay->weekday] = explode(',', $workDay->hours);
        }

        return $workDaysByWeekday;
    }

    private function doesBarberWorkOnWeekday($weekday, $workdays): bool
    {
        return in_array($weekday, array_keys($workdays));
    }

    private function findAvailabilityForDay($day, $workdays, $appointments)
    {
        $availability = null;
        $currentTime = strtotime('+' . $day . ' days');
        $currentWeekday = date('w', $currentTime);

        if ($this->doesBarberWorkOnWeekday($currentWeekday, $workdays)) {
            $formattedDay = date('Y-m-d', $currentTime);
            $availableHours = $this->findBarberAvailableHoursOnDay(
                $formattedDay,
                $currentWeekday,
                $workdays,
                $appointments
            );

            if (!empty($availableHours)) {
                $availability = new BarberAvailabilityDto($formattedDay, $availableHours);
            }
        }

        return $availability;
    }

    private function findBarberAvailableHoursOnDay(
        $day,
        $weekday,
        $workdays,
        $appointments
    ): array
    {
        $availableHours = [];

        foreach ($workdays[$weekday] as $hour) {
            if (!$this->doesBarberHaveAppointmentsOn($day, $hour, $appointments)) {
                $availableHours[] = $hour;
            }
        }

        return $availableHours;
    }

    private function doesBarberHaveAppointmentsOn($day, $hour, $appointments): bool
    {
        $formattedDateTime = $day . ' ' . $hour . ':00';

        return in_array($formattedDateTime, $appointments);
    }

    public function hasAvailabilityOnWeekday($weekday, $barberId): bool
    {
        $availability = BarberAvailability::select()
            ->where('id_barber', $barberId)
            ->where('weekday', $weekday)
            ->first();

        return ($availability != null);
    }
}
