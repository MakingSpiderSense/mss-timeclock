<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfilesController extends Controller
{
    // Update the profile
    public function update() 
    {
        // Validate the request
        $data = request()->validate([
            'name' => 'required',
            // Make sure the email is unique, but ignore the current user's email. Also make sure it's a valid email.
            'email' => 'required|email|unique:users,email,' . auth()->user()->id,
            'nickname' => 'required',
            'username' => 'required|unique:users,username,' . auth()->user()->id,
        ]);
        // Update the profile
        auth()->user()->update(array_merge(
            $data,
            // $imageArray ?? []
        ));
        // Redirect to the named route 'profile'
        return redirect()->route('profile');
    }
}
