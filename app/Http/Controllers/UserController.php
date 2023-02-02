<?php

namespace App\Http\Controllers;

use App\Models\Barber;
use App\Models\UserFavorite;
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
}
