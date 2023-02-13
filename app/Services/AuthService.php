<?php

namespace App\Services;

use App\Models\Dto\NewUserDto;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Auth\Authenticatable;

/**
 * Responsible for providing authentication services.
 */
class AuthService
{
    // ------------------------------------------------------------------------
    //         Attributes
    // ------------------------------------------------------------------------
    private UserService $userService;


    // ------------------------------------------------------------------------
    //         Constructor
    // ------------------------------------------------------------------------
    public function __construct()
    {
        $this->userService = new UserService();
    }


    // ------------------------------------------------------------------------
    //         Methods
    // ------------------------------------------------------------------------
    public function getAuthenticatedUser(): Authenticatable
    {
        return auth()->user();
    }

    public function signin($email, $password)
    {
        $credentials = [
            'email' => $email,
            'password' => $password
        ];
        $token = auth()->attempt($credentials);

        if (!$token) {
            throw new AuthorizationException('Unauthorized');
        }

        return $this->buildTokenStructure($token);
    }

    private function buildTokenStructure($token){
        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => auth()->user()
        ];
    }

    public function signout()
    {
        auth()->logout();
    }

    public function refresh()
    {
        return $this->buildTokenStructure(auth()->refresh());
    }

    public function signup(NewUserDto $newUser)
    {
        $this->userService->create($newUser);

        return $this->signin($newUser->getEmail(), $newUser->getPassword());
    }
}
