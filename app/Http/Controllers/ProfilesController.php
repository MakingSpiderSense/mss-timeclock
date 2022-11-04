<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfilesController extends Controller
{
    // Update the profile
    public function update() 
    {
        // Check if image is null
        $img_null = false;
        if (request('image') == null) {
            // If image is null, remove the image from the request
            request()->offsetUnset('image');
            $img_null = true;
        }
        // Validate the request
        $data = request()->validate([
            'name' => 'required|string|max:255',
            // Make sure the email is unique, but ignore the current user's email. Also make sure it's a valid email.
            'email' => 'required|email|max:255|unique:users,email,' . auth()->user()->id,
            'nickname' => 'max:255',
            'username' => 'required|max:255|unique:users,username,' . auth()->user()->id,
            'image' => 'image|mimes:jpeg,png,jpg,gif|max:128', // 128 KB
        ]);
        // If there is an image, store it
        // TODO: Update to automatically compress the image to 128 KB
        if (request('image')) {
            // Store the image in public/uploads/profile
            $imagePath = request('image')->store('public/uploads/profile');
            // Remove the public/uploads/ from the path
            $imagePath = str_replace('public/uploads/profile/', '', $imagePath);
        }
        $imageArray = $img_null ? [] : ['avatar_url' => $imagePath];
        // Update the profile
        auth()->user()->update(array_merge(
            $data,
            $imageArray ?? []
        ));
        // Redirect to the named route 'profile'
        return redirect()->route('profile');
    }
}
