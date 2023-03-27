<?php
/*
 * Copyright (c) William Niemiec.
 *
 * This source code is licensed under the MIT license found in the
 * LICENSE file in the root directory of this source tree.
 */

namespace App\Http\Controllers;

use App\Dto\UpdateUserDto;
use App\Services\AuthService;
use App\Services\UserAppointmentService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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
        $this->userAppointmentService = new UserAppointmentService();
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
                ->name($request->input('name'))
                ->email($request->input('email'))
                ->password($request->input('password'))
                ->build()
        );

        return response()->statusCode(200);
    }

    public function uploadAvatar(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'avatar' => 'required|image|mimes:png,jpg,jpeg'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $this->userService->uploadAvatar(
            $request->file('avatar'),
            $this->authService->getAuthenticatedUser()->id
        );

        return response()->statusCode(201);
    }
}
