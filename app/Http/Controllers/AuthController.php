<?php
/*
 * Copyright (c) William Niemiec.
 *
 * This source code is licensed under the MIT license found in the
 * LICENSE file in the root directory of this source tree.
 */

namespace App\Http\Controllers;

use App\Models\Dto\NewUserDto;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class AuthController extends Controller
{
    // ------------------------------------------------------------------------
    //         Attributes
    // ------------------------------------------------------------------------
    private readonly AuthService $authService;


    // ------------------------------------------------------------------------
    //         Constructor
    // ------------------------------------------------------------------------
    public function __construct()
    {
        $this->middleware('auth:api', [
            'except' => ['signin', 'signup']
        ]);
        $this->authService = new AuthService();
    }

    public function signin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        return $this->authService->signin(
            $request->input('email'),
            $request->input('password')
        );
    }

    public function signout() {
        $this->authService->signout();
    }

    public function refresh() {
        return $this->authService->refresh();
    }

    public function signup(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $newUser = NewUserDto::builder()
            ->name($request->input('name'))
            ->avatar($request->input('avatar'))
            ->email($request->input('email'))
            ->password($request->input('password'))
            ->build();

        $response = $this->authService->signup($newUser);

        return response()->json($response);
    }
}
