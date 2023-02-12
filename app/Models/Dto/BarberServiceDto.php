<?php

namespace App\Models\Dto;

use App\Models\BarberService;

class BarberServiceDto implements \JsonSerializable
{
    // ------------------------------------------------------------------------
    //         Attributes
    // ------------------------------------------------------------------------
    private readonly int $id;
    private readonly string $name;
    private readonly float $price;


    // ------------------------------------------------------------------------
    //         Constructor
    // ------------------------------------------------------------------------
    public function __construct(BarberService $service)
    {
        $this->id = $service->id;
        $this->name = $service->name;
        $this->price = $service->price;
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
            'price' => $this->price
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

    public function getPrice()
    {
        return $this->price;
    }
}
