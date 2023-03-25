<?php
/*
 * Copyright (c) William Niemiec.
 *
 * This source code is licensed under the MIT license found in the
 * LICENSE file in the root directory of this source tree.
 */

 namespace App\Models\Dto;

 use App\Models\Barber;

 class BarberMiniDto implements \JsonSerializable
 {
    // ------------------------------------------------------------------------
    //         Attributes
    // ------------------------------------------------------------------------
    private readonly int $id;
    private string $name;
    private string $avatar;
    private int $stars;
    private float $latitude;
    private float $longitude;


    // ------------------------------------------------------------------------
    //         Constructor
    // ------------------------------------------------------------------------
    public function __construct(Barber $barber)
    {
        $this->id = $barber->id;
        $this->name = $barber->name;
        $this->avatar = $barber->avatar;
        $this->stars = $barber->stars;
        $this->latitude = $barber->latitude;
        $this->longitude = $barber->longitude;
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
            'name' => $this->name,
            'avatar' => $this->avatar,
            'stars' => $this->stars,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude
        );
    }


    // ------------------------------------------------------------------------
    //         Getters
    // ------------------------------------------------------------------------
    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getAvatar()
    {
        return $this->avatar;
    }

    public function getStars()
    {
        return $this->stars;
    }

    public function getLatitude()
    {
        return $this->latitude;
    }

    public function getLongitude()
    {
        return $this->longitude;
    }
}
