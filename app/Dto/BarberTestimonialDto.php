<?php
/*
 * Copyright (c) William Niemiec.
 *
 * This source code is licensed under the MIT license found in the
 * LICENSE file in the root directory of this source tree.
 */

namespace App\Dto;

use App\Models\BarberTestimonial;

class BarberTestimonialDto implements \JsonSerializable
{
    // ------------------------------------------------------------------------
    //         Attributes
    // ------------------------------------------------------------------------
    private readonly int $id;
    private readonly string $title;
    private readonly float $rate;
    private readonly string $body;
    private readonly UserDto $user;


    // ------------------------------------------------------------------------
    //         Constructor
    // ------------------------------------------------------------------------
    public function __construct(BarberTestimonial $testimonial)
    {
        $this->id = $testimonial->id;
        $this->title = $testimonial->title;
        $this->rate = $testimonial->rate;
        $this->body = $testimonial->body;
        $this->user = new UserDto($testimonial->user);
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
            'title' => $this->title,
            'rate' => $this->rate,
            'body' => $this->body,
            'user' => $this->user
        );
    }


    // ------------------------------------------------------------------------
    //         Getters
    // ------------------------------------------------------------------------
    public function getId()
    {
        return $this->id;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getRate()
    {
        return $this->rate;
    }

    public function getBody()
    {
        return $this->body;
    }

    public function getUser()
    {
        return $this->user;
    }
}
