<?php

namespace App\Http\Controllers;

use Validator;
use App\Models\User;
use App\Models\Organization;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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

        // Make sure that the user is not already subscribed or invited to the organization.
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

    // Retrieve the logged in user's invitations
    public function show_invitations() {
        // Retrieve all rows from the user_org_subscriptions table where the user_id is the logged in user's id and the status is 'invited'.
        $invitations = auth()->user()->subscriptions()->where('status', 'invited')->get();
        return $invitations;
    }

    // Accept an invitation
    public function accept_invitation(Request $request) {
        // Update the user_org_subscriptions table to change the status from 'invited' to 'subscribed' for the logged in user and the organization id passed in the request.
        auth()->user()->subscriptions()->updateExistingPivot($request->org_id, ['status' => 'subscribed']);
        return 'Invitation accepted.';
    }

    // Decline an invitation
    public function decline_invitation(Request $request) {
        // Delete the row from the user_org_subscriptions table for the logged in user and the organization id passed in the request.
        auth()->user()->subscriptions()->detach($request->org_id);
        return 'Invitation declined.';
    }

    // Set active organization to the organization id passed in the request
    public function set_active(Request $request) {
        // Check if the logged in user is subscribed to the organization id passed in the request.
        if ( auth()->user()->subscriptions()->where('org_id', $request->org_id)->where('status', '!=', 'invited')->exists() ) {
            // Update the active_org_id column in the users table to the organization id passed in the request.
            auth()->user()->update(['active_org_id' => $request->org_id]);
            $org_name = Organization::where('id', $request->org_id)->first()->name;
            return redirect()->back()->with('message', ['success', "Active organization set to $org_name"]);
        } else {
            return redirect()->back()->with('message', ['error', 'You are not subscribed to that organization.']);
        }
    }
}
