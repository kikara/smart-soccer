<?php

namespace App\Http\Controllers\UserProfile;

use App\Http\Controllers\Controller;
use App\Models\UserSingleAudio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

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
            'login' => 'required|min:6|max:255',
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
        $params['userSounds'] = UserSingleAudio::where('user_id', '=', Auth()->user()->id)->get()?->toArray() ?? [];
        return view('user.sounds_settings', $params);
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
            custom_log($filePath, false);
            UserSingleAudio::destroy($data['id']);
            return ['result' => true];
        }
        return ['result' => false];
    }
}
