<?php

namespace App\Http\Controllers;

use App\Models\Barber;
use Illuminate\Http\Request;

class BarberController extends Controller
{
    private $loggedUser;

    public function __construct()
    {
        $this->middleware('auth:api');
        $this->loggedUser = auth()->user();
    }

    public function list()
    {
        $array = ['error' => ''];

        $array['data'] = Barber::all();
        $array['loc'] = 'Porto Alegre';

        foreach ($array['data'] as $key => $value) {
            $array['data'][$key]['avatar'] = url('media/avatars/' . $array['data'][$key]['avatar']);
        }

        return response()->json($array);
    }
}
