<?php

namespace App\Models\Dto;

class BarberAvailabilityDto
{
    // ------------------------------------------------------------------------
    //         Attributes
    // ------------------------------------------------------------------------
    private readonly string $date;
    private readonly array $hours;


    // ------------------------------------------------------------------------
    //         Constructor
    // ------------------------------------------------------------------------
    public function __construct(string $date, array $hours)
    {
        $this->date = $date;
        $this->hours = $hours;
    }


    // ------------------------------------------------------------------------
    //         Getters
    // ------------------------------------------------------------------------
    public function getDate()
    {
        return $this->date;
    }

    public function getHours()
    {
        return $this->hours;
    }
}

