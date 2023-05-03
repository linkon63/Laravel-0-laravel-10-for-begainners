<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateAvatarRequest;
use Illuminate\Http\Request;

class AvatarController extends Controller
{
    //
    public function update(UpdateAvatarRequest $request)
    {
        // 
        // $request->validate(([
        //     'avatar' => 'required|image',
        // ]));
        dd($request->all());
        // dd($request->input('_token'));

        return back()->with('message', 'Avatar has changed');
    }
};
