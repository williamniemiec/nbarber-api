<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', [
            'except' => ['signin', 'signup']
        ]);
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

        $token = auth()->attempt($validator->validated());

        if (!$token) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->createNewToken($token);
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function createNewToken($token){
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => auth()->user()
        ]);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function signout() {
        auth()->logout();
        return response()->json(['message' => 'User successfully signed out']);
    }
    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh() {
        return $this->createNewToken(auth()->refresh());
    }
    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function userProfile() {
        return response()->json(auth()->user());
    }

    /**
     * Register a User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function signup(Request $request)
    {
        $response = ['error' => ''];
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            $response['error'] = 'Incorrect data';
        }
        else {
            if ($this->hasUserWithEmail($request->input('email'))) {
                $response['error'] = 'The email has already been taken';
            }
            else {
                $newUser = new User($request->only('name', 'email', 'password'));
                $newUser->password = password_hash($newUser->password, PASSWORD_DEFAULT);
                $newUser->save();

                $token = auth()->attempt([
                    'email' => $newUser->email,
                    'password' => $request->input('password'),
                ]);

                if ($token) {
                    $response['token'] = $token;
                    $response['user'] = auth()->user();
                }
                else {
                    $response['error'] = 'Error on JWT generation';
                }
            }
        }

        return $response;
    }

    private function hasUserWithEmail(string $email)
    {
        return (User::where('email', $email)->count() > 0);
    }
}
