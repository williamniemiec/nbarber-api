<?php

namespace App\Http\Controllers;

use App\Models\Barber;
use App\Models\BarberService;
use App\Models\User;
use App\Models\UserAppointment;
use App\Models\UserFavorite;
use App\Services\AuthService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

class UserController extends Controller
{
    // ------------------------------------------------------------------------
    //         Attributes
    // ------------------------------------------------------------------------
    private readonly UserService $userService;
    private readonly AuthService $authService;


    // ------------------------------------------------------------------------
    //         Constructor
    // ------------------------------------------------------------------------
    public function __construct()
    {
        $this->middleware('auth:api');
        $this->userService = new UserService();
        $this->authService = new AuthService();
    }


    // ------------------------------------------------------------------------
    //         Methods
    // ------------------------------------------------------------------------
    public function read()
    {
        $authenticatedUser = $this->authService->getAuthenticatedUser();
        $user = $this->userService->findById($authenticatedUser->id);

        return response()->json($user);
    }

    public function getFavorites(Request $request)
    {
        $response = ['error' => ''];

        $rawFavorites = UserFavorite::select()
            ->where('id_user', $this->loggedUser->id)
            ->get();
        $favoriteIds = [];
        foreach ($rawFavorites as $favorite) {
            $favoriteIds[] = $favorite->id_barber;
        }
        $favorites = array_map(function ($barberId) {
            $barber = Barber::find($barberId);

            $barber->avatar = url('/media/avatars/' . $barber->avatar);

            return $barber;
        }, $favoriteIds);
        $response['data'] = $favorites;

        return response()->json($response);
    }

    public function toggleFavorite(Request $request)
    {
        $response = ['error' => '', 'removed' => false];

        $barberId = intVal($request->input('barber'));

        if (!$barberId) {
            return ['error' => 'barber field is required'];
        }

        $barber = Barber::find($barberId);

        if (!$barber) {
            return ['error' => 'barber does not exist'];
        }

        $favorite = UserFavorite::select()
            ->where('id_user', $this->loggedUser->id)
            ->where('id_barber', $barberId)
            ->first();

        if ($favorite) {
            $favorite->delete();
            $response['removed'] = true;
        }
        else {
            $newFavorite = new UserFavorite();
            $newFavorite->id_user = $this->loggedUser->id;
            $newFavorite->id_barber = $barberId;
            $newFavorite->save();
        }

        return response()->json($response);
    }

    public function getAppointments(Request $request)
    {
        $response = ['error' => ''];

        $appointments = [];
        $rawAppointments = UserAppointment::select()
            ->where('id_user', $this->loggedUser->id)
            ->orderBy('date', 'DESC')
            ->get();

        if ($rawAppointments) {
            foreach ($rawAppointments as $appointment) {
                $barber = Barber::find($appointment->id_barber);
                $barber->avatar = url('media/avatars/' . $barber->avatar);

                $service = BarberService::find($appointment->id_service);
                $formattedAppointment = [
                    'id' => $appointment->id,
                    'date' => $appointment->date,
                    'barber' => $barber
                ];
                $formattedAppointment['service'] = $service;

                $appointments[] = $formattedAppointment;
            }
        }

        $response['data'] = $appointments;


        return response()->json($response);
    }

    public function update(Request $request)
    {
        $rules = [
            'name' => 'min:2',
            'email' => 'email|unique:users',
            'password' => 'same:password_confirm',
            'password_confirm' => 'same:password'
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return ['error' => $validator->messages()];
        }

        $data = $validator->validated();
        $user = User::find($this->loggedUser->id);

        if ($data['name']) {
            $user->name = $data['name'];
        }

        if ($data['email']) {
            $user->email = $data['email'];
        }

        if ($data['password']) {
            $user->password = $data['password'];
        }

        $user->save();
    }

    public function uploadAvatar(Request $request)
    {
        $rules = [
            'avatar' => 'required|image|mimes:png,jpg,jpeg'
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return ['error' => $validator->messages()];
        }

        $avatar = $request->file('avatar');
        $filename = $this->storeAvatar($avatar);

        $user = User::find($this->loggedUser->id);
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
