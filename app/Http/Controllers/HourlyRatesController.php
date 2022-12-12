<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HourlyRatesController extends Controller
{
    public function __construct()
    {
        // Force the user to be logged in to access these functions. This will redirect them to the login page if they are not.
        $this->middleware('auth');
    }

    // Load the hourly rates page with the current user's hourly rates
    public function index()
    {
        // Look up the user's global rate
        $global_rate = auth()->user()->global_rate;

        // In the user_org_subscriptions table, create an array of objects with the `user_org_subscriptions` id, `organizations` name, and the `user_org_subscriptions rate for each organization the user is subscribed to
        $organization_rates = auth()->user()->subscriptions()->select('user_org_subscriptions.id', 'name', 'rate')->orderBy('name', 'asc')->get();
        
        // Find the active_org_id in the `users` table. Set $categories_with_rates to list of the current users rates on the user_category table where the org_id in the `categories` table matches the active_org_id. Get the id, name, and rate and list in ascending order by name.
        $categories_with_rates = auth()->user()->categories()->where('org_id', auth()->user()->active_org_id)->select('user_category.id', 'name', 'rate')->orderBy('name', 'asc')->get();

        return inertia('Rates', [
            'global_rate' => $global_rate,
            'organization_rates' => $organization_rates,
            'categories_with_rates' => $categories_with_rates,
        ]);
    }
}
