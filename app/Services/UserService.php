<?php

namespace App\Services;

use App\Exceptions\DataIntegrityException;
use App\Models\Dto\NewUserDto;
use App\Models\User;
use App\Models\UserFavorite;
use Illuminate\Contracts\Auth\Authenticatable;

/**
 * Responsible for providing user services.
 */
class UserService
{
    // ------------------------------------------------------------------------
    //         Attributes
    // ------------------------------------------------------------------------
    private readonly Authenticatable $loggedUser;


    // ------------------------------------------------------------------------
    //         Constructor
    // ------------------------------------------------------------------------
    public function __construct()
    {
        $this->loggedUser = auth()->user();
    }


    // ------------------------------------------------------------------------
    //         Methods
    // ------------------------------------------------------------------------
    public function hasFavorited($barberId)
    {
        $favorited = UserFavorite::where([
            ['id_user', '=', $this->loggedUser->id],
            ['id_barber', '=', $barberId]
        ])->count();

        return ($favorited > 0);
    }

    public function hasUserWithEmail(string $email)
    {
        return (User::where('email', $email)->count() > 0);
    }

    public function create(NewUserDto $user)
    {
        if ($this->hasUserWithEmail($user->getEmail())) {
            throw new DataIntegrityException('The email has already been taken');
        }

        $newUser = new User($user->toArray());
        $newUser->password = password_hash($user->getPassword(), PASSWORD_DEFAULT);
        $newUser->save();
    }
}
