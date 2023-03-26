<?php
/*
 * Copyright (c) William Niemiec.
 *
 * This source code is licensed under the MIT license found in the
 * LICENSE file in the root directory of this source tree.
 */

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
    //         Factories
    // ------------------------------------------------------------------------
    public static function empty()
    {
        return new BarberSearchResultDto([], "");
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
