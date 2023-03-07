<?php
/*
 * Copyright (c) William Niemiec.
 *
 * This source code is licensed under the MIT license found in the
 * LICENSE file in the root directory of this source tree.
 */

namespace App\Models\Dto;

class BarberDto implements \JsonSerializable
{
    // ------------------------------------------------------------------------
    //         Attributes
    // ------------------------------------------------------------------------
    private ?int $id;
    private ?string $name;
    private ?string $avatar;
    private ?int $stars;
    private ?float $latitude;
    private ?float $longitude;
    private ?array $services;
    private ?array $testimonials;
    private ?bool $favorited;
    private ?array $photos;
    private ?array $availability;


    // ------------------------------------------------------------------------
    //         Constructor
    // ------------------------------------------------------------------------
    public function __construct(
        $barber,
        $services,
        $testimonials,
        $favorited,
        $photos,
        $availability
    )
    {
        $this->id = $barber->id;
        $this->name = $barber->name;
        $this->avatar = $barber->avatar;
        $this->stars = $barber->stars;
        $this->latitude = $barber->latitude;
        $this->longitude = $barber->longitude;
        $this->services = $services;
        $this->testimonials = $testimonials;
        $this->favorited = $favorited;
        $this->photos = $photos;
        $this->availability = $availability;
    }


    // ------------------------------------------------------------------------
    //         Builder
    // ------------------------------------------------------------------------
    public static function builder()
    {
        return new class
        {
            private ?string $_barber;
            private ?array $_services;
            private ?array $_testimonials;
            private ?bool $_favorited;
            private ?array $_photos;
            private ?array $_availability;

            public function barber($value)
            {
                $this->_barber = $value;
                return $this;
            }

            public function services($value)
            {
                $this->_services = $value;
                return $this;
            }

            public function testimonials($value)
            {
                $this->_testimonials = $value;
                return $this;
            }

            public function favorited($value)
            {
                $this->_favorited = $value;
                return $this;
            }

            public function availability($value)
            {
                $this->_availability = $value;
                return $this;
            }

            public function photos($value)
            {
                $this->_photos = $value;
                return $this;
            }

            public function build()
            {
                return new BarberDto(
                    $this->_barber,
                    $this->_services,
                    $this->_testimonials,
                    $this->_favorited,
                    $this->_photos,
                    $this->_availability
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
            'name' => $this->name,
            'avatar' => $this->avatar,
            'stars' => $this->stars,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'services' => json_encode($this->services),
            'testimonials' => json_encode($this->testimonials),
            'favorited' => $this->favorited,
            'photos' => json_encode($this->photos),
            'availability' => json_encode($this->availability)
        );
    }


    // ------------------------------------------------------------------------
    //         Getters
    // ------------------------------------------------------------------------
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

    public function getServices()
    {
        return $this->services;
    }

    public function getTestimonials()
    {
        return $this->testimonials;
    }

    public function getFavorited()
    {
        return $this->favorited;
    }

    public function getPhotos()
    {
        return $this->photos;
    }

    public function getAvailability()
    {
        return $this->availability;
    }
}
