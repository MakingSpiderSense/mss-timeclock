<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class RatesController extends Controller
{
    public function __construct()
    {
        // Force the user to be logged in to access these functions. This will redirect them to the login page if they are not.
        $this->middleware('auth');
    }

    // Load the rates page with the current user's rates
    public function index()
    {
        // Look up the user's global rate
        $global_rate = auth()->user()->global_rate;

        // In the user_org_subscriptions table, create an array of objects with the `user_org_subscriptions` id, `organizations` name, and the `user_org_subscriptions rate for each organization the user is subscribed to
        $organization_rates = auth()->user()->subscriptions()->select('user_org_subscriptions.id', 'name', 'rate')->orderBy('name', 'asc')->get();
        
        // Find the active_org_id in the `users` table. Set $categories_with_rates to list of the current users rates on the user_category table where the org_id in the `categories` table matches the active_org_id. Get the id, name, and rate and list in ascending order by name.
        $categories_with_rates = auth()->user()->categories()->where('org_id', auth()->user()->active_org_id)->select('user_category.id', 'category_id', 'name', 'rate')->orderBy('name', 'asc')->get();

        // Set $categories_without_rates to an array of the categories in the in the `categories` table where the org_id is the user's active_org_id. If the category `id` is in the $categories_with_rates array, skip it and do not add it to this array. Get the id, name, and rate and list in ascending order by name.
        $categories_without_rates = Category::where('org_id', auth()->user()->active_org_id)->whereNotIn('id', $categories_with_rates->pluck('category_id'))->select('categories.id', 'name')->orderBy('name', 'asc')->get();

        // Return the Rates page with the variables
        return inertia('Rates', [
            'global_rate' => $global_rate,
            'organization_rates' => $organization_rates,
            'categories_with_rates' => $categories_with_rates,
            'categories_without_rates' => $categories_without_rates,
        ]);
    }

    // Update the user's rates
    public function update(Request $request)
    {
        dd($request);
    }
}
