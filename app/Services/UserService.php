<?php

namespace App\Services;

use App\Exceptions\DataIntegrityException;
use App\Exceptions\ObjectNotFoundException;
use App\Models\Dto\NewUserDto;
use App\Models\Dto\UserDto;
use App\Models\User;
use App\Models\UserFavorite;

/**
 * Responsible for providing user services.
 */
class UserService
{
    // ------------------------------------------------------------------------
    //         Attributes
    // ------------------------------------------------------------------------
    private readonly BarberService $barberService;
    private readonly UserAppointmentService $appointmentService;


    // ------------------------------------------------------------------------
    //         Constructor
    // ------------------------------------------------------------------------
    public function __construct()
    {
        $this->barberService = new BarberService();
        $this->appointmentService = new UserAppointmentService();
    }


    // ------------------------------------------------------------------------
    //         Methods
    // ------------------------------------------------------------------------
    public function hasFavorited($userId, $barberId)
    {
        $favorited = UserFavorite::where([
            ['id_user', '=', $userId],
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

    public function findById($id): UserDto
    {
        $user = User::find($id);

        if (!$user) {
            throw new ObjectNotFoundException($id);
        }

        return UserDto::builder()
            ->id($user->id)
            ->name($user->name)
            ->avatar(url('media/avatars/' . $user->avatar))
            ->email($user->email)
            ->build();
    }
}
