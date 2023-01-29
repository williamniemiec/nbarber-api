<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    private $loggedUser;

    public function __construct()
    {
        $this->middleware('auth:api');
        $this->loggedUser = auth()->user();
    }

    public function read()
    {
        $array = ['error' => ''];
        $user = $this->loggedUser;
        $user['avatar'] = url('media/avatars/' . $user['avatar']);
        $array['data'] = $user;

        return response()->json($array);
    }
}
