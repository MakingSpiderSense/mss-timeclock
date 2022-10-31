<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use Illuminate\Http\Request;
use Validator;

class OrganizationsController extends Controller
{
    public function __construct()
    {
        // Force the user to be logged in to access these functions. This will redirect them to the login page if they are not.
        $this->middleware('auth');
    }
    
    // Store the organization
    public function store(Request $request)
    {
        // Validate the request
        // Note: Below, we use this array to post data to the database. If there is any data missing from the array, such as data that doesn't need to be validated, we can add it to the array as a key, but leave the value an empty string.
        $rules = [
            'org_name' => 'required|string|max:255|unique:organizations,name',
        ];
        // We use the Validator::make method so that we can output the errors as a flash message, similar to how we flash success messages. If we don't use this, we can simply set $data = request()->validate($rules);.
        $validator = Validator::make( $request->all(), $rules );
        if ( $validator->fails() ) {
            return redirect('/dashboard')->with('message', ['error', $validator->errors()->first()]);
        }
        $data = request();
        
        // Create the organization
        Organization::create([
            'name' => $data['org_name'],
        ]);

        // Subscribe the user to the organization
        $organization = Organization::where('name', $data['org_name'])->first();
        // Attach the user to the organization with the role of admin and include created_at and updated_at timestamps.
        auth()->user()->subscriptions()->attach($organization->id, ['role' => 'admin', 'created_at' => now(), 'updated_at' => now()]);
        
        // Redirect to the profile page of logged in user
        return redirect('/dashboard')->with('message', ['success', 'Organization created successfully.']);
    }

    // Invite a user to the organization
    public function invite()
    {   
        // Redirect to the profile page of logged in user
        return redirect('/dashboard')->with('message', ['success', 'User invited to [organization].']);
        
    }
}
