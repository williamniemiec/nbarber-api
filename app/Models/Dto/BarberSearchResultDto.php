<?php

namespace App\Models\Dto;

class BarberSearchResultDto implements \JsonSerializable
{
    // ------------------------------------------------------------------------
    //         Attributes
    // ------------------------------------------------------------------------
    private array $barbers;
    private ?string $location;


    // ------------------------------------------------------------------------
    //         Constructor
    // ------------------------------------------------------------------------
    public function __construct(array $barbers, string $location)
    {
        $this->barbers = $barbers;
        $this->location = $location;
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
            'barbers' => $this->barbers,
            'location' => $this->location
        );
    }


    // ------------------------------------------------------------------------
    //         Getters
    // ------------------------------------------------------------------------
    public function getBarbers()
    {
        return $this->barbers;
    }

    public function getLocation()
    {
        return $this->location;
    }
}
