<?php

namespace App\Http\Controllers\UserProfile;

use App\Http\Controllers\Controller;
use App\Models\EventParam;
use App\Models\Events;
use App\Models\UserAudioEvent;
use App\Models\UserSingleAudio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class UserProfileController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('user.profile');
    }

    public function saveProfile(Request $request)
    {
        $validated = $request->validate([
            'login' => 'required|max:255',
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|email:rfc,dns',
            'avatar' => 'file',
        ]);
        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('avatars');
        }
        $user = auth()->user();
        $updateData = [
            'login' => $validated['login'],
            'name' => $validated['name'] ?? '',
            'email' => $validated['email'] ?? '',
        ];
        if (isset($path)) {
            $updateData['avatar_path'] = $path;
        }
        $user->update($updateData);
        return redirect('profile');
    }

    public function sounds()
    {
        $userId = Auth()->user()->id;
        $params['userSounds'] = UserSingleAudio::where('user_id', '=', $userId)->get()?->toArray() ?? [];
        $params['userEventSounds'] = $this->getUserEventSounds($userId);
        $params['paramsInfo'] = $this->getParamsInfo();
        return view('user.sounds_settings', $params);
    }

    public function getUserEventSounds($userId)
    {
        $result = [];
        $events = UserAudioEvent::where('user_id', '=', $userId)
                ->leftJoin('events', 'user_audio_events.event_id', '=', 'events.id')
                ->select('user_audio_events.*', 'events.name as event_name')
                ->get()?->toArray() ?? [];

        foreach ($events as $event) {
            if (! empty($event['parameters'])) {
                $decoded = json_decode($event['parameters'], true) ?: [];
                $event['decoded_parameters'] = $decoded;
            }
            $result[] = $event;
        }
        return $result;
    }

    public function getParamsInfo()
    {
        $params = EventParam::all()?->toArray() ?? [];
        $result = [];
        foreach ($params as $param) {
            $result[$param['code']] = $param['description'];
        }
        return $result;
    }

    public function saveGoalSound(Request $request)
    {
        if ($request->hasFile('single-sound')) {
            $userID = Auth()->user()->id;
            $name = $request->file('single-sound')->getClientOriginalName();
            $path = $request->file('single-sound')->store('user_single_goal');
            $audio = UserSingleAudio::create([
                'user_id' => $userID,
                'name' => $name,
                'path' => $path
            ]);
            $params['sound'] = [
                'id' => $audio->id,
                'name' => $name,
                'path' => $path,
            ];
            return [
                'result' => true,
                'template' => view('user.layouts.audio_container', $params)->render(),
            ];
        }
        return ['result' => false];
    }

    public function deleteSingleAudio(Request $request)
    {
        $data = $request->all();
        if ($data['id']) {
            $audio = UserSingleAudio::find($data['id']);
            $path = $audio->path;
            $filePath = $_SERVER['DOCUMENT_ROOT'] . '/storage/' . $path;
            File::delete($filePath);
            UserSingleAudio::destroy($data['id']);
            return ['result' => true];
        }
        return ['result' => false];
    }

    public function saveEvent(Request $request)
    {
        try {
            $validated = $request->validate([
                'event' => 'required|integer|min:1',
                'sound' => 'required|file',
                'params' => 'string|required'
            ]);
        } catch (ValidationException $exception) {
            return [
                'result' => false,
                'error' => $exception->getMessage(),
            ];
        }
        if ($request->hasFile('sound')) {
            $userID = Auth()->user()->id;
            $name = $request->file('sound')->getClientOriginalName();
            $path = $request->file('sound')->store('audio_events');
            $event = UserAudioEvent::create([
                'user_id' => $userID,
                'event_id' => $validated['event'],
                'path' => $path,
                'audio_name' => $name,
                'parameters' => $validated['params']
            ]);
            $params['paramsInfo'] = $this->getParamsInfo();
            $params['eventSound'] = [
                'id' => $event->id,
                'event_name' => Events::find($validated['event'])->name,
                'decoded_parameters' => json_decode($validated['params'], true) ?: [],
                'audio_name' => $name,
                'path' => $path,
            ];
            return ['result' => true, 'content' => view('user.layouts.audio_event_container', $params)->render()];
        }
        return ['result' => false, 'error' => 'File is required'];
    }

    public function eventDelete(Request $request)
    {
        $data = $request->all();
        if ($data['id']) {
            $event = UserAudioEvent::find($data['id']);
            $path = $event->path;
            $filePath = $_SERVER['DOCUMENT_ROOT'] . '/storage/' . $path;
            File::delete($filePath);
            UserAudioEvent::destroy($data['id']);
            return ['result' => true];
        }
        return ['result' => false];
    }
}
