<?php

namespace App\Http\Controllers\Popup;

use App\Http\Controllers\Controller;
use App\Models\EventParam;
use App\Models\Events;
use App\Models\User;
use Illuminate\Http\Request;

class PopupController extends Controller
{
    public function getStartGameForm()
    {
        $params['users'] = User::all()->toArray();
        return view('popup.start', $params);
    }

    public function addEventPopup(Request $request)
    {
        $params['events'] = Events::all()?->toArray() ?? [];
        $params['parameters'] = EventParam::all()?->toArray() ?? [];
        return [
            'result' => true,
            'content' => view('user.popup.create_event', $params)->render(),
        ];
    }
}
