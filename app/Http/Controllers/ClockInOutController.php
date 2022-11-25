<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\TempLog;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Organization;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ClockInOutController extends Controller
{
    public function __construct()
    {
        // Force the user to be logged in to access these functions. This will redirect them to the login page if they are not.
        $this->middleware('auth');
    }

    // Index function
    public function index()
    {
        // Get the user's current organization's id
        $current_org_id = auth()->user()->active_org_id;
        // Get the user's current organization's categories and sort them by name
        $categories = Category::where('org_id', $current_org_id)->orderBy('name')->get();
        // For each category, get the subcategories and sort them by name
        foreach ($categories as $category) {
            $category->subcategories = Subcategory::where('category_id', $category->id)->orderBy('name')->get();
        }
        // Inertia render dashboard view with categories and subcategories
        return inertia('Dashboard', [
            'categoriesObj' => $categories,
        ]);
    }

    // Clock in/out
    public function clockInOut(Request $request)
    {
        // Check if the user is currently clocked in
        if (auth()->user()->clocked_in) {
            // If they are, clock them out
            $this->clockOut($request);
        } else {
            // If they are not, clock them in
            $this->clockIn($request);
        }
    }

    // Clock in
    public function clockIn(Request $request)
    {
        // Validate request
        $request->validate([
            'category' => 'required|string|max:255',
            'manualTime' => 'nullable|date_format:Y-m-d H:i:s',
            'subcategory' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        // dd($request->category, $request->subcategory, $request->manualTime, $request->notes);

        // Check to see if they are already clocked in, if not, update the user's clocked_in field to true
        if (auth()->user()->clocked_in == false) {
            // Update the user's clocked_in field to true
            User::where('id', auth()->user()->id)->update(['clocked_in' => true]);
        } else {
            // If they are already clocked in, return an error
            return redirect()->back()->with('message', ['error', 'You are already clocked in.']);
        }

        // Store the category (if applicable)
        // If the category name doesn't exist in the "categories" table for the logged in user's active organization, create it.
        Category::firstOrCreate([
            'name' => $request->category, 
            'org_id' => auth()->user()->active_org_id
        ]);
        // Get the category id where name is equal to the category name and the org_id is equal to the logged in user's active organization id
        $category_id = Category::where('name', $request->category)->where('org_id', auth()->user()->active_org_id)->first()->id;
        // Set the subcategory id to null
        $subcategory_id = null;

        // Store the subcategory (if applicable)
        // If the subcategory is not null, set it to the request. Otherwise, set it to "Other".
        if ($request->subcategory != null) {
            $subcategory_name = $request->subcategory;
        } else {
            $subcategory_name = "Other";
        }
        // If the subcategory name doesn't exist in the "subcategories" table for the $category_id, create it.
        Subcategory::firstOrCreate([
            'name' => $subcategory_name, 
            'category_id' => $category_id
        ]);
        // Get the subcategory id where name is equal to the subcategory name and the category_id is equal to the $category_id
        $subcategory_id = Subcategory::where('name', $subcategory_name)->where('category_id', $category_id)->first()->id;

        // dd($category_id, $subcategory_id);

        // Store the clock in time
        // If the manual time is not null, set it to the request. Otherwise, set it to the current time.
        if ($request->manualTime != null) {
            $clock_in = $request->manualTime;
        } else {
            $clock_in = now();
        }
        // Create a new temp log with the user_id, subcategory_id, clock_in, and notes
        auth()->user()->tempLogs()->create([
            'subcategory_id' => $subcategory_id,
            'clock_in' => $clock_in,
            'notes' => $request->notes,
        ]);

        // Get name of organization based on the user's auth()->user()->active_org_id
        $org_id = auth()->user()->active_org_id;
        // Look up the organization name based on the org_id
        $org_name = Organization::where('id', $org_id)->first()->name;

        // Redirect to dashboard with success message, "Clocked into [organization name] > [category name] > [subcategory name] successfully."
        return redirect()->back()->with('message', ['success', 'Clocked into "' . $org_name . ' > ' . $request->category . ' > ' . $subcategory_name . '" successfully.']);
    }

    // Clock out
    public function clockOut(Request $request) {
        // Validate request
        $request->validate([
            'manualTime' => 'nullable|date_format:Y-m-d H:i:s',
        ]);

        // Check to see if they are already clocked out. If not, update the user's clocked_in field to true
        if (auth()->user()->clocked_in == true) {
            // Update the user's clocked_in field to true
            User::where('id', auth()->user()->id)->update(['clocked_in' => false]);
        } else {
            // If they are already clocked in, return an error
            return redirect()->back()->with('message', ['error', 'You are already clocked out.']);
        }

        // Store the clock out time
        // If the manual time is not null, set it to the request. Otherwise, set it to the current time.
        if ($request->manualTime != null) {
            $clock_out = $request->manualTime;
        } else {
            $clock_out = now();
        }
        // Update the clocked in temp log with the clock_out time
        $temp_log = TempLog::where('user_id', auth()->user()->id)->where('clock_out', null)->orderBy('created_at', 'desc')->first();
        // Update the temp log with the clock_out time, but do not update the clocked_in timestamp
        $temp_log->update([
            'clock_in' => DB::raw('clock_in'),
            'clock_out' => $clock_out,
        ]);

        // Redirect to dashboard with success message, "Clocked have clocked out. Good work!"
        return redirect()->back()->with('message', ['success', 'Clocked have clocked out. Good work!']);
    }
}
