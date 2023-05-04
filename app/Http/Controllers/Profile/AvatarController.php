<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateAvatarRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AvatarController extends Controller
{
    //
    public function update(UpdateAvatarRequest $request)
    {
        // 
        // $request->validate(([
        //     'avatar' => 'required|image',
        // ]));
        // dd($request->file('avatar'));
        // dd($request->input('_token'));

        // $path = $request->file('avatar')->store('avatars', 'public');
        $path = Storage::disk('public')->put('avatars', $request->file('avatar'));
        dd($path);
        // check there is previous image/avatar are there or not
        if ($oldAvatar = $request->user()->avatar) {
            // dd($request->user()->avatar);
            // then delete the previous image and set new one
            Storage::disk('public')->delete($oldAvatar);
        }


        // dd($path);
        auth()->user()->update(['avatar' => $path]);
        // auth()->user()->update(['avatar' => storage_path('app') . "/$path"]);
        // dd(auth()->user());
        // $request->file('avatar')->store('avatar');
        return redirect(route('profile.edit'))->with('message', 'Avatar has changed');
        // return back()->with('message', 'Avatar has changed');
    }
};
