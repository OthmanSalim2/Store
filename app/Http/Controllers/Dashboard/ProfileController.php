<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\Intl\Countries;
use Symfony\Component\Intl\Languages;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();

        return view('dashboard.profile.edit', [
            'user' => $user,
            'countries' => Countries::getNames(),
            'locales' => Languages::getNames(),
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'first_name' => ['string', 'required', 'max:255'],
            'last_name' => ['string', 'required', 'max:255'],
            'birthday' => ['nullable', 'date', 'before:today'],
            'gender' => ['in:male,female'],
            'country' => ['required', 'string', 'size:2'],
        ]);

        $user = $request->user();

        $user->profile->fill($request->all())->save();

        return redirect()->route('dashboard.profile.edit')
            ->with('success', 'Profile updated');

        // other way to check if exist profile or no for user
        // $profile = $user->profile;
        // if ($profile->user_id) {
        //     $user->profile->update($request->all());
        // } else {

        //     // user_id already receive from $user, mean on this id create  profile
        //     // request->all() don't contain of user_id so user_id take from relation
        //     $user->profile()->create($request->all());

        //     // other way to create new profile
        //     // $request->merge([
        //     //     'user_id' => $user->id
        //     // ]);
        //     // Profile::create($request->all());
        // }
    }
}
