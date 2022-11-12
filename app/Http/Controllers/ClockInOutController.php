<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Http\Request;

class ClockInOutController extends Controller
{
    public function __construct()
    {
        // Force the user to be logged in to access these functions. This will redirect them to the login page if they are not.
        $this->middleware('auth');
    }

    // Clock in
    public function clockIn(Request $request)
    {
        // Validate request
        $request->validate([
            'category' => 'required|string|max:255',
            'manualTime' => 'nullable|date_format:H:i',
            'subcategory' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

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
        // If the subcategory is not null...
        if ($request->subcategory) {
            // If the subcategory name doesn't exist in the "subcategories" table for the $category_id, create it.
            Subcategory::firstOrCreate([
                'name' => $request->subcategory, 
                'category_id' => $category_id
            ]);
            // Get the subcategory id where name is equal to the subcategory name and the category_id is equal to the $category_id
            $subcategory_id = Subcategory::where('name', $request->subcategory)->where('category_id', $category_id)->first()->id;
        }
        dd($category_id, $subcategory_id);
    }
}
