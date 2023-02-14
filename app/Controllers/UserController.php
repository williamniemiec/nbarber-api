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
        $authenticatedUser = $this->authService->getAuthenticatedUser();
        $response = $this->userService->findFavoritesByUserId($authenticatedUser->id);

        return response()->json($response);
    }

    public function toggleFavorite(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'barber' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $barberId = intVal($request->input('barber'));
        $response['removed'] = $this->userService->toggleFavorite(
            $this->authService->getAuthenticatedUser()->id,
            $barberId
        );

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
