<?php

namespace App\Models\Dto;

class BarberAvailabilityDto implements \JsonSerializable
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
    //         Methods
    // ------------------------------------------------------------------------
    /**
     * {@inheritDoc}
     *  @see \JsonSerializable::jsonSerialize()
     *
     *  @Override
     */
    public function jsonSerialize(): array
    {
        return array(
            'date' => $this->date,
            'hours' => $this->hours
        );
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

