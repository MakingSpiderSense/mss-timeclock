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

        return inertia('Rates', [
            'global_rate' => $global_rate,
        ]);
    }
}
