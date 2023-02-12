<?php

namespace App\Services;

use Illuminate\Contracts\Auth\Authenticatable;

/**
 * Responsible for providing authentication services.
 */
class AuthService
{
    // ------------------------------------------------------------------------
    //         Attributes
    // ------------------------------------------------------------------------



    // ------------------------------------------------------------------------
    //         Constructor
    // ------------------------------------------------------------------------
    public function __construct()
    {
    }


    // ------------------------------------------------------------------------
    //         Methods
    // ------------------------------------------------------------------------
    public function getAuthenticatedUser(): Authenticatable
    {
        return auth()->user();
    }

}
