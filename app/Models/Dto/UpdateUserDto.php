<?php
/*
 * Copyright (c) William Niemiec.
 *
 * This source code is licensed under the MIT license found in the
 * LICENSE file in the root directory of this source tree.
 */

namespace App\Models\Dto;

class UpdateUserDto
{
    // ------------------------------------------------------------------------
    //         Attributes
    // ------------------------------------------------------------------------
    private readonly ?string $name;
    private readonly ?string $email;
    private readonly ?string $password;


    // ------------------------------------------------------------------------
    //         Constructor
    // ------------------------------------------------------------------------
    public function __construct($name, $email, $password)
    {
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
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

            public function build()
            {
                return new UpdateUserDto(
                    $this->_name,
                    $this->_email,
                    $this->_password
                );
            }
        };
    }


    // ------------------------------------------------------------------------
    //         Getters
    // ------------------------------------------------------------------------
    public function getName()
    {
        return $this->name;
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
