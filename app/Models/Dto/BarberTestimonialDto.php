<?php

namespace App\Models\Dto;

use App\Models\BarberTestimonial;

class BarberTestimonialDto
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
