<?php
/*
 * Copyright (c) William Niemiec.
 *
 * This source code is licensed under the MIT license found in the
 * LICENSE file in the root directory of this source tree.
 */

namespace App\Dto;

class BarberSearchDto implements \JsonSerializable
{
    // ------------------------------------------------------------------------
    //         Attributes
    // ------------------------------------------------------------------------
    private ?float $latitude;
    private ?float $longitude;
    private ?string $city;
    private ?int $offset;
    private ?int $limit;


    // ------------------------------------------------------------------------
    //         Constructor
    // ------------------------------------------------------------------------
    public function __construct(
        $latitude,
        $longitude,
        $city,
        $offset,
        $limit
    )
    {
        $this->latitude = $latitude;
        $this->longitude = $longitude;
        $this->city = $city;
        $this->offset = $offset;
        $this->limit = $limit;
    }


    // ------------------------------------------------------------------------
    //         Builder
    // ------------------------------------------------------------------------
    public static function builder()
    {
        return new class
        {
            private ?float $_latitude;
            private ?float $_longitude;
            private ?string $_city;
            private ?int $_offset;
            private ?int $_limit;

            public function latitude($value)
            {
                $this->_latitude = $value;
                return $this;
            }

            public function longitude($value)
            {
                $this->_longitude = $value;
                return $this;
            }

            public function city($value)
            {
                $this->_city = $value;
                return $this;
            }

            public function offset($value)
            {
                $this->_offset = $value;
                return $this;
            }

            public function limit($value)
            {
                $this->_limit = $value;
                return $this;
            }

            public function build()
            {
                return new BarberSearchDto(
                    $this->_latitude,
                    $this->_longitude,
                    $this->_city,
                    $this->_offset,
                    $this->_limit
                );
            }
        };
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
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'city' => $this->city,
            'offset' => $this->offset,
            'limit' => $this->limit
        );
    }


    // ------------------------------------------------------------------------
    //         Getters
    // ------------------------------------------------------------------------
    public function getLatitude()
    {
        return $this->latitude;
    }

    public function getLongitude()
    {
        return $this->longitude;
    }

    public function getCity()
    {
        return $this->city;
    }

    public function getOffset()
    {
        return $this->offset;
    }

    public function getLimit()
    {
        return $this->limit;
    }
}
