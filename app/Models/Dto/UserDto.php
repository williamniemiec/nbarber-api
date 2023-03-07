<?php
/*
 * Copyright (c) William Niemiec.
 *
 * This source code is licensed under the MIT license found in the
 * LICENSE file in the root directory of this source tree.
 */

namespace App\Models\Dto;

use App\Models\User;

class UserDto implements \JsonSerializable
{
    // ------------------------------------------------------------------------
    //         Attributes
    // ------------------------------------------------------------------------
    private readonly ?int $id;
    private readonly ?string $name;
    private readonly ?string $avatar;
    private readonly ?string $email;


    // ------------------------------------------------------------------------
    //         Constructor
    // ------------------------------------------------------------------------
    public function __construct(?User $user)
    {
        $this->id = $user->id;
        $this->name = $user->name;
        $this->avatar = $user->avatar;
        $this->email = $user->email;
    }


    // ------------------------------------------------------------------------
    //         Builder
    // ------------------------------------------------------------------------
    public static function builder()
    {
        return new class
        {
            private ?int $_id;
            private ?string $_name;
            private ?string $_email;
            private ?string $_avatar;

            public function id($value)
            {
                $this->_id = $value;
                return $this;
            }

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

            public function avatar($value)
            {
                $this->_avatar = $value;
                return $this;
            }

            public function build()
            {
                return new UserDto(
                    new User([
                        'id' => $this->_id,
                        'name' => $this->_name,
                        'avatar' => $this->_avatar,
                        'email' => $this->_email
                    ])
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
            'email' => $this->email
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

    public function getEmail()
    {
        return $this->email;
    }
}
