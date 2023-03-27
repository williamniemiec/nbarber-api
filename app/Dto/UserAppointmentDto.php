<?php
/*
 * Copyright (c) William Niemiec.
 *
 * This source code is licensed under the MIT license found in the
 * LICENSE file in the root directory of this source tree.
 */

namespace App\Dto;

class UserAppointmentDto implements \JsonSerializable
{
    // ------------------------------------------------------------------------
    //         Attributes
    // ------------------------------------------------------------------------
    private readonly ?int $id;
    private readonly ?string $date;
    private readonly ?BarberMiniDto $barber;
    private readonly ?BarberServiceDto $service;


    // ------------------------------------------------------------------------
    //         Constructor
    // ------------------------------------------------------------------------
    public function __construct($id, $date, $barber, $service)
    {
        $this->id = $id;
        $this->date = $date;
        $this->barber = $barber;
        $this->service = $service;
    }


    // ------------------------------------------------------------------------
    //         Builder
    // ------------------------------------------------------------------------
    public static function builder()
    {
        return new class
        {
            private ?int $_id;
            private ?string $_date;
            private ?BarberMiniDto $_barber;
            private ?BarberServiceDto $_service;

            public function id($value)
            {
                $this->_id = $value;
                return $this;
            }

            public function date($value)
            {
                $this->_date = $value;
                return $this;
            }

            public function barber($value)
            {
                $this->_barber = $value;
                return $this;
            }

            public function service($value)
            {
                $this->_service = $value;
                return $this;
            }

            public function build()
            {
                return new UserAppointmentDto(
                    $this->_id,
                    $this->_date,
                    $this->_barber,
                    $this->_service
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
            'id' => $this->id,
            'date' => $this->date,
            'barber' => $this->barber,
            'service' => $this->service
        );
    }


    // ------------------------------------------------------------------------
    //         Getters
    // ------------------------------------------------------------------------
    public function getId()
    {
        return $this->id;
    }

    public function getDate()
    {
        return $this->date;
    }

    public function getBarber()
    {
        return $this->barber;
    }

    public function getService()
    {
        return $this->service;
    }
}
