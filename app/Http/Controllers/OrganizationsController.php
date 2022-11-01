<?php

namespace App\Http\Controllers;

use Validator;
use App\Models\User;
use App\Models\Organization;
use Illuminate\Http\Request;

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
    public function invite(Request $request)
    {   
        // Todo: Make this dynamic based on current organization
        $test_org_id = 3;
        $test_org_name = Organization::where('id', $test_org_id)->first()->name;

        // Make sure that the User::where('email', $data['invite_email'])->first() is not already subscribed or invited to the organization.
        $check_if_subscribed_or_invited = function($attribute, $value, $fail) use ($test_org_id) {
            $user_to_invite = User::where('email', $value)->first();
            if (!$user_to_invite) {
                return;
            } else if ( $user_to_invite->subscriptions()->where('org_id', $test_org_id)->exists() ) {
                $fail('The user is already invited or subscribed to the organization.');
            }
        };

        // Validate the request
        // Note: Below, we use this array to post data to the database. If there is any data missing from the array, such as data that doesn't need to be validated, we can add it to the array as a key, but leave the value an empty string.
        $rules = [
            'invite_email' => ['required', 'email', 'max:255', 'exists:users,email', $check_if_subscribed_or_invited],
        ];
        // We use the Validator::make method so that we can output the errors as a flash message, similar to how we flash success messages. If we don't use this, we can simply set $data = request()->validate($rules);.
        $validator = Validator::make( $request->all(), $rules );
        if ( $validator->fails() ) {
            return redirect('/dashboard')->with('message', ['error', $validator->errors()->first()]);
        }
        $data = request();

        // Subscribe the user to the organization
        $invited_user = User::where('email', $data['invite_email'])->first();
        // Attach the user to the organization with the role of admin and include created_at and updated_at timestamps.
        $invited_user->subscriptions()->attach($test_org_id, ['role' => 'user', 'status' => 'invited', 'created_at' => now(), 'updated_at' => now()]);
        // Redirect to the profile page of logged in user
        return redirect('/dashboard')->with('message', ['success', "User invited to $test_org_name."]);
    }
}
