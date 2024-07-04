<?php

namespace App\Http\Controllers;

use App\Models\HiddenCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SettingsController extends Controller
{
    public function __construct()
    {
        // Force the user to be logged in to access these functions. This will redirect them to the login page if they are not.
        $this->middleware('auth');
    }

    // Load the settings page
    public function index()
    {
        return inertia('Settings/Settings');
    }

    // Display the hidden categories page
    public function show_hidden_categories()
    {
        $user = Auth::user();
        $hiddenCategories = HiddenCategory::with(['organization', 'category', 'subcategory'])
            ->where('user_id', $user->id)
            ->get();
        return inertia('Settings/HiddenCategories', [
            'hiddenCategories' => $hiddenCategories,
        ]);
    }

    // Update stats view
    public function updateStatsView()
    {
        // Cycle through the stats view options
        if (auth()->user()->set_combined_org == "combined_org") {
            auth()->user()->set_combined_org = "combined_org_minus_tax";
        } elseif (auth()->user()->set_combined_org == "combined_org_minus_tax") {
            auth()->user()->set_combined_org = "current_org";
        } elseif (auth()->user()->set_combined_org == "current_org") {
            auth()->user()->set_combined_org = "current_org_minus_tax";
        } elseif (auth()->user()->set_combined_org == "current_org_minus_tax") {
            auth()->user()->set_combined_org = "combined_org";
        }
        // Save the user's updated set_combined_org setting
        auth()->user()->save();
        // Redirect back to the current page
        return back();
    }
}
