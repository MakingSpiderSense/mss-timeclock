<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TimeLogController extends Controller
{
    public function __construct()
    {
        // Force the user to be logged in to access these functions. This will redirect them to the login page if they are not.
        $this->middleware('auth');
    }

    // Load the time log page
    public function index()
    {
        // Return the Time Log page
        return inertia('Reports/TimeLog');
    }
}
