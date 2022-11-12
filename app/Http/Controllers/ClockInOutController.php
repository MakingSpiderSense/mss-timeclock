<?php

namespace App\Http\Controllers;

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

        dd($request->all());
    }
}
