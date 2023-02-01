<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\PermLog;
use App\Models\TempLog;
use Illuminate\Support\Facades\DB;
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

        // Check that the userAccounts field is a list of comma-separated integers (spaces are allowed)
        if (!preg_match('/^(\d+\s*,\s*)*\d+$/', $request->userAccounts)) {
            return redirect()->back()->with('message', ['error', 'Invalid user accounts.']);
        }
        // Convert the userAccounts field to an array of integers
        $request->userAccounts = explode(',', $request->userAccounts);

        // Check to make sure all user accounts exist
        foreach ($request->userAccounts as $userAccount) {
            if (!User::find($userAccount)) {
                return redirect()->back()->with('message', ['error', 'One or more user accounts do not exist.']);
            }
        }

        // For each user account, consolidate their time logs. To do this, for each subcategory_id in the temp_logs table, sum up the minutes. Then insert a new row into the perm_logs table with the user_id, subcategory_id, minutes, and month.
        foreach ($request->userAccounts as $userAccount) {
            // Get the user's subcategory_id's and minutes
            $tempLogs = TempLog::select('subcategory_id', DB::raw('SUM(minutes) as minutes'))
                ->where('user_id', $userAccount)
                // ->where('created_at', 'like', $request->month . '%')
                ->groupBy('subcategory_id')
                ->get();

            // For each subcategory_id, insert a new row into the perm_logs table
            foreach ($tempLogs as $tempLog) {
                PermLog::create([
                    'user_id' => $userAccount,
                    'subcategory_id' => $tempLog->subcategory_id,
                    'minutes' => $tempLog->minutes,
                    'month' => $request->month,
                ]);
            }
        }

        // Success message
        return redirect()->back()->with('message', ['success', 'Time logs consolidated.']);
    }
}
