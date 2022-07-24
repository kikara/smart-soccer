<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function getMainLayout()
    {
        $params['data'] = request()->all();
        $params['data']['blue_gamer_name'] = User::find($params['data']['blue_gamer'])->toArray();
        $params['data']['red_gamer_name'] = User::find($params['data']['red_gamer'])->toArray();
        return view('game', $params);
    }

    public function getMainInfo()
    {
        $data = request()->all();
        $data['blue_gamer_name'] = User::find($data['blue_gamer'])->toArray();
        $data['red_gamer_name'] = User::find($data['red_gamer'])->toArray();
        return $data;
    }
}
