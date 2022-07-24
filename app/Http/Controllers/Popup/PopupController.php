<?php

namespace App\Http\Controllers\Popup;

use App\Http\Controllers\Controller;
use App\Models\User;

class PopupController extends Controller
{
    public function getStartGameForm()
    {
        $params['users'] = User::all()->toArray();
        return view('popup.start', $params);
    }
}