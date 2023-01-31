<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SuperAdminController extends Controller
{
    // Constructor with abort if user id is that of a super admin
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (auth()->user()->id != 1 && auth()->user()->id != 17) {
                abort(403);
            }
            return $next($request);
        });
    }

    // Dashboard
    public function index()
    {
        return inertia('SuperAdmin');
    }

    // Consolidate all users' time logs
    public function consolidateTimeLogs(Request $request)
    {
        // Validate request (`month` and `userAccounts` are required)
        $request->validate([
            'month' => 'required|date_format:Y-m',
            'userAccounts' => 'required',
        ]);
        dd($request);
    }
}
