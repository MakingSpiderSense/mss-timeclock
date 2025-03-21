<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\TempLog;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\HiddenCategory;
use App\Models\Organization;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
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
        // Get the user's hidden categories
        $hidden_categories = HiddenCategory::where('user_id', auth()->user()->id)->get();
        // Filter the categories and subcategories based on hidden categories.
        $filteredCategories = $categories->map(function ($category) use ($hidden_categories) {
            // Clone the category to avoid altering the original
            $clonedCategory = clone $category;
            // Filters the subcategories of each cloned category to exclude those that are marked as hidden for the user
            $clonedCategory->subcategories = $clonedCategory->subcategories->filter(function ($subcategory) use ($hidden_categories, $category) {
                return $hidden_categories->where('category_id', $category->id)->where('subcategory_id', $subcategory->id)->count() === 0;
            });
            return $clonedCategory;
        })->filter(function ($category) use ($hidden_categories) {
            // If the category is marked as hidden for the user, it is excluded from the filtered list
            return $hidden_categories->where('category_id', $category->id)->whereNull('subcategory_id')->count() === 0;
        });
        // Inertia render dashboard view with categories and subcategories
        return inertia('Dashboard', [
            'categoriesObj' => $categories,
            'filteredCategoriesObj' => $filteredCategories,
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
        $validator = \Validator::make($request->all(), [
            'org_id' => 'integer',
            'category' => 'required|string|max:255',
            'manualTime' => 'nullable|date_format:Y-m-d H:i:s',
            'subcategory' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->with('message', ['error', 'Validation Error: ' . $validator->errors()->first()])->withInput();
        }

        // dd($request->category, $request->subcategory, $request->manualTime, $request->notes, $request->org_id);

        // Check to see org got out of sync between devices
        if ($request->org_id && $request->org_id != auth()->user()->active_org_id) {
            return redirect()->back()->with('message', ['error', 'The organization may have gotten out of sync between your devices. Please refresh the page.']);
        }

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

        // Store category info in recent_subcategories
        // Retrieve the user's current recent subcategories
        $recent = auth()->user()->recent_subcategories ?? [];
        // Ensure it's an array (in case it's stored as JSON string)
        if (!is_array($recent)) {
            $recent = json_decode($recent, true);
        }
        // Create the new entry (subcategory, category, organization)
        $org_id = auth()->user()->active_org_id;
        $newEntry = [
            'subcategory_id' => $subcategory_id,
            'category_id' => $category_id,
            'org_id' => $org_id
        ];
        // Remove duplicate if it exists
        $recent = array_filter($recent, function ($item) use ($subcategory_id) {
            return $item['subcategory_id'] !== $subcategory_id;
        });
        // Add new entry to the front
        array_unshift($recent, $newEntry);
        // Keep only the latest 5
        $recent = array_slice($recent, 0, 5);
        // Save back to the user record
        auth()->user()->update(['recent_subcategories' => json_encode($recent)]);

        // Look up the organization name based on the org_id
        $org_name = Organization::where('id', $org_id)->first()->name;

        // Redirect to dashboard with success message, "Clocked into [organization name] → [category name] → [subcategory name] successfully."
        return redirect()->back()->with('message', ['success', 'Clocked into "' . $org_name . ' → ' . $request->category . ' → ' . $subcategory_name . '" successfully.']);
    }

    // Clock out
    public function clockOut(Request $request) {
        // Validate request
        $request->validate([
            'manualTime' => 'nullable|date_format:Y-m-d H:i:s',
            'notes' => 'nullable|string',
        ]);

        // Check to see if they are already clocked out.
        if (auth()->user()->clocked_in == false) {
            return redirect()->back()->with('message', ['error', 'You are already clocked out.']);
        }

        // Get the temp log entry for the user
        $temp_log = TempLog::where('user_id', auth()->user()->id)->where('clock_out', null)->orderBy('created_at', 'desc')->first();

        // Set $clock_in to the temp log's clock_in time
        $clock_in = $temp_log->clock_in;
        $clock_in = Carbon::parse($clock_in);

        // If the manual time is not null, set it to the request. Otherwise, set it to the current time.
        if ($request->manualTime != null) {
            $clock_out = $request->manualTime;
            // Convert from string to Carbon object
            $clock_out = Carbon::parse($clock_out);
        } else {
            $clock_out = now();
        }

        // If the clock_out is before the clock_in, return an error
        if ($clock_out->lt($clock_in)) {
            return redirect()->back()->with('message', ['error', 'The clock out time cannot be before the clock in time.']);
        }

        // Get the difference in minutes between the clock_out and clock_in times
        $diff_in_minutes = $clock_out->diffInMinutes($clock_in);
        // Round the difference to the nearest minute based on the seconds
        $seconds = $clock_out->diffInSeconds($clock_in);
        if ($seconds % 60 >= 30) {
            $diff_in_minutes = $diff_in_minutes + 1;
        }
        // Get hours and minutes
        $hours = floor($diff_in_minutes / 60);
        $hours_label_suffix = $hours == 1 ? '' : 's';
        $minutes = $diff_in_minutes % 60;
        $minutes_label_suffix = $minutes == 1 ? '' : 's';

        // Update the user's clocked_in field to true
        User::where('id', auth()->user()->id)->update(['clocked_in' => false]);

        // Update the temp log with the clock_out time, but do not update the clocked_in timestamp
        $temp_log->update([
            'clock_in' => DB::raw('clock_in'),
            'clock_out' => $clock_out,
            'minutes' => $diff_in_minutes,
            'notes' => $request->notes,
        ]);

        // Redirect to dashboard with success message, "You have clocked out. Good work!"
        return redirect()->back()->with('message', ['success', "You have clocked out with $hours hour$hours_label_suffix and $minutes minute$minutes_label_suffix on the clock. Good work!"]);
    }

    // Cancel clock in
    public function cancelClockIn() {
        // Check to see if they are already clocked out.
        if (auth()->user()->clocked_in == false) {
            return redirect()->back()->with('message', ['error', 'You are already clocked out.']);
        }

        // Delete the temp log entry for the user
        TempLog::where('user_id', auth()->user()->id)->where('clock_out', null)->orderBy('created_at', 'desc')->first()->delete();

        // Update the user's clocked_in field to true
        User::where('id', auth()->user()->id)->update(['clocked_in' => false]);

        // Redirect to dashboard with success message
        return redirect()->back()->with('message', ['success', 'Cancelled the clock in.']);
    }

    // Hide category
    public function hideCategory(Request $request, $type) {
        // Validate request
        $request->validate([
            'category' => 'required|string|max:255',
            'subcategory' => 'nullable|string|max:255',
        ]);
        // Get the user's current organization's id
        $current_org_id = auth()->user()->active_org_id;
        // Set the category name and subcategory name based on the type
        if ($type == 'category') {
            $category_name = $request->category;
            $subcategory_name = null;
        } else {
            $category_name = $request->category;
            $subcategory_name = $request->subcategory;
        }
        // If the category name doesn't exist, return an error
        if (Category::where('name', $category_name)->where('org_id', $current_org_id)->count() == 0) {
            return redirect()->back()->with('message', ['error', 'The category does not exist.']);
        }
        // Get the category id based on the category name and the current org
        $category_id = Category::where('name', $category_name)->where('org_id', $current_org_id)->first()->id;
        // If we are hiding the subcategory, find the subcategory id
        $subcategory_id = null;
        if ($subcategory_name) {
            // If the subcategory name doesn't exist, return an error
            if (Subcategory::where('name', $subcategory_name)->where('category_id', $category_id)->count() == 0) {
                return redirect()->back()->with('message', ['error', 'The subcategory does not exist.']);
            }
            // Get the subcategory id based on the subcategory name and the category id
            $subcategory_id = Subcategory::where('name', $subcategory_name)->where('category_id', $category_id)->first()->id;
        }
        // If the entry already exists in the hidden_categories table, return an error
        if (HiddenCategory::where('user_id', auth()->user()->id)->where('category_id', $category_id)->where('subcategory_id', $subcategory_id)->count() > 0) {
            return redirect()->back()->with('message', ['error', 'The ' . $type . ' "' . $category_name . ($subcategory_name != null ? ' > ' . $subcategory_name : '') . '" is already hidden.']);
        }
        // Create a new hidden category entry
        HiddenCategory::create([
            'user_id' => auth()->user()->id,
            'category_id' => $category_id,
            'subcategory_id' => $subcategory_id,
        ]);
        // Redirect to dashboard with success message
        return redirect()->back()->with('message', ['success', 'The ' . $type . ' "' . $category_name . ($subcategory_name != null ? ' > ' . $subcategory_name : '') . '" has been hidden.']);
    }
}
