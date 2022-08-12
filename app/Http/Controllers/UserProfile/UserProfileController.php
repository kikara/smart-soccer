<?php

namespace App\Http\Controllers\UserProfile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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
}
