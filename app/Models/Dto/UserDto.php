<?php

namespace App\Models\Dto;

use App\Models\User;

class UserDto
{
    // ------------------------------------------------------------------------
    //         Attributes
    // ------------------------------------------------------------------------
    private readonly int $id;
    private readonly string $name;
    private readonly string $avatar;
    private readonly string $email;


    // ------------------------------------------------------------------------
    //         Constructor
    // ------------------------------------------------------------------------
    public function __construct(User $user)
    {
        $this->id = $user->id;
        $this->name = $user->name;
        $this->avatar = $user->avatar;
        $this->email = $user->email;
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

    public function getEmail()
    {
        return $this->email;
    }
}
