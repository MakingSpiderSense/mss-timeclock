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
            'name' => 'required|string|max:255',
            // Make sure the email is unique, but ignore the current user's email. Also make sure it's a valid email.
            'email' => 'required|email|max:255|unique:users,email,' . auth()->user()->id,
            'nickname' => 'required|max:255',
            'username' => 'required|max:255|unique:users,username,' . auth()->user()->id,
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
