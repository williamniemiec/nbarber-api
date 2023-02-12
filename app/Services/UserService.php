<?php

namespace App\Services;

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
}
