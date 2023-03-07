<?php
/*
 * Copyright (c) William Niemiec.
 *
 * This source code is licensed under the MIT license found in the
 * LICENSE file in the root directory of this source tree.
 */

namespace App\Models\Dto;

class NewUserDto
{
    // ------------------------------------------------------------------------
    //         Attributes
    // ------------------------------------------------------------------------
    private readonly ?string $name;
    private readonly ?string $email;
    private readonly ?string $password;
    private readonly ?string $avatar;


    // ------------------------------------------------------------------------
    //         Constructor
    // ------------------------------------------------------------------------
    public function __construct($name, $email, $password, $avatar)
    {
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->avatar = $avatar;
    }


    // ------------------------------------------------------------------------
    //         Builder
    // ------------------------------------------------------------------------
    public static function builder()
    {
        return new class
        {
            private ?string $_name;
            private ?string $_email;
            private ?string $_password;
            private ?string $_avatar;

            public function name($value)
            {
                $this->_name = $value;
                return $this;
            }

            public function email($value)
            {
                $this->_email = $value;
                return $this;
            }

            public function password($value)
            {
                $this->_password = $value;
                return $this;
            }

            public function avatar($value)
            {
                $this->_avatar = $value;
                return $this;
            }

            public function build()
            {
                return new NewUserDto(
                    $this->_name,
                    $this->_email,
                    $this->_password,
                    $this->_avatar,
                );
            }
        };
    }


    // ------------------------------------------------------------------------
    //         Methods
    // ------------------------------------------------------------------------
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'avatar' => $this->avatar,
            'email' => $this->email,
            'password' => $this->password
        ];
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

    public function getEmail()
    {
        return $this->email;
    }

    public function getPassword()
    {
        return $this->password;
    }
}
