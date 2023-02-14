<?php

namespace App\Http\Controllers;

use App\Models\Barber;
use App\Models\BarberService;
use App\Models\Dto\UpdateUserDto;
use App\Models\User;
use App\Models\UserAppointment;
use App\Models\UserFavorite;
use App\Services\AuthService;
use App\Services\UserAppointmentService;
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
    private readonly UserAppointmentService $userAppointmentService;


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
        $appointments = $this->userAppointmentService->findAppointmentsByUserId(
            $this->authService->getAuthenticatedUser()->id
        );

        return response()->json($appointments);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'min:2',
            'email' => 'email|unique:users',
            'password' => 'same:password_confirm',
            'password_confirm' => 'same:password'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $this->userService->update(
            $this->authService->getAuthenticatedUser()->id,
            UpdateUserDto::builder()
                ->name($request->input('name')),
                ->email($request->input('email'))
                ->password($request->input('password'))
                ->build()
        );

        return response()->status(200);
    }

    public function uploadAvatar(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'avatar' => 'required|image|mimes:png,jpg,jpeg'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $filename = $this->userService->uploadAvatar(
            $request->file('avatar'),
            $this->authService->getAuthenticatedUser()->id
        );

        return respones()->status(201);
    }
}
