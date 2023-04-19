<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        // Get user's log data
        // For each entry in the `temp_logs` table for the user (user_id), we need to get the id, subcategory_id, clock_in, clock_out, minutes, and notes. But then we also need to use the subcategory_id to look into the `subcategories` table to get the category_id and name. Then we need to use the category_id to look into the `categories` and get the org_id and name. Finally, we need to use the org_id to look into the `organizations` table and get the name. We need to do this so that we can display the category and organization names on the time log page. We also need to order the results by clock_in in descending order.
        $user_id = auth()->user()->id;
        $logs = DB::table('temp_logs')
            ->join('subcategories', 'temp_logs.subcategory_id', '=', 'subcategories.id')
            ->join('categories', 'subcategories.category_id', '=', 'categories.id')
            ->join('organizations', 'categories.org_id', '=', 'organizations.id')
            ->leftJoin('users', 'temp_logs.user_id', '=', 'users.id')
            ->select('temp_logs.id', 'temp_logs.subcategory_id', 'temp_logs.clock_in', 'temp_logs.clock_out', 'temp_logs.minutes', 'temp_logs.notes', 'subcategories.category_id', 'subcategories.name as subcategory_name', 'categories.org_id', 'categories.name as category_name', 'organizations.name as organization_name', 'users.time_zone')
            ->where('temp_logs.user_id', $user_id)
            ->orderByDesc('temp_logs.clock_in')
            ->get();

        // Adjust each log's clock_in and clock_out times to the user's time zone if they have one set
        foreach ($logs as $log) {
            if ($log->time_zone) {
                $log->clock_in = Carbon::parse($log->clock_in)->timezone($log->time_zone);
                $log->clock_out = Carbon::parse($log->clock_out)->timezone($log->time_zone);
            }
        }

        // Return the Time Log page with the variables
        return inertia('Reports/TimeLog', [
            'logs' => $logs,
        ]);
    }
}
