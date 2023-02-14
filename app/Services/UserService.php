<?php

namespace App\Services;

use App\Exceptions\DataIntegrityException;
use App\Exceptions\ObjectNotFoundException;
use App\Models\Dto\NewUserDto;
use App\Models\Dto\UpdateUserDto;
use App\Models\Dto\UserDto;
use App\Models\User;
use App\Models\UserFavorite;
use Intervention\Image\Facades\Image;

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
    public function findFavoritesByUserId($userId): array
    {
        $favorites = $this->findFavoritedBarbers($userId);

        return array_map(
            fn($barberId) => $this->barberService->findById($barberId),
            $favorites
        );
    }

    private function findFavoritedBarbers($userId): array
    {
        $favorites = UserFavorite::select()
            ->where('id_user', $userId)
            ->get()
            ->toArray();

        return array_map(fn($favorite) => $favorite->id_barber, $favorites);
    }

    public function hasFavorited($userId, $barberId)
    {
        $favorited = UserFavorite::where([
            ['id_user', '=', $userId],
            ['id_barber', '=', $barberId]
        ])->count();

        return ($favorited > 0);
    }

    public function toggleFavorite($userId, $barberId)
    {
        $removed = false;
        $barber = $this->barberService->findById($barberId);
        $favorite = UserFavorite::select()
            ->where('id_user', $userId)
            ->where('id_barber', $barberId)
            ->first();

        if ($favorite) {
            $favorite->delete();
            $removed = true;
        }
        else {
            $newFavorite = new UserFavorite();
            $newFavorite->id_user = $userId;
            $newFavorite->id_barber = $barber->id;
            $newFavorite->save();
        }

        return $removed;
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

    public function update($id, UpdateUserDto $updatedUser): void
    {
        $user = User::find($id);

        if ($updatedUser->getName()) {
            $user->name = $updatedUser->getName();
        }

        if ($updatedUser->getEmail()) {
            $user->email = $updatedUser->getEmail();
        }

        if ($updatedUser->getPassword()) {
            $user->password = $updatedUser->getPassword();
        }

        $user->save();
    }

    public function uploadAvatar($avatar, $userId)
    {
        $filename = $this->storeAvatar($avatar);

        $user = User::find($userId);
        $user->avatar = $filename;
        $user->save();
    }

    private function storeAvatar($avatar): string
    {
        $destination = public_path('/media/avatars');
        $filename = $this->generateRandomName() . '.jpg';
        $img = Image::make($avatar->getRealPath());
        $img
            ->fit(300, 300)
            ->save($destination.'/'.$filename);

        return $filename;
    }

    private function generateRandomName(): string
    {
        return md5(time() . rand(0, 9999));
    }
}
