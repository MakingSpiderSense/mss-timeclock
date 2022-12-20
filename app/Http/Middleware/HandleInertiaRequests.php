<?php

namespace App\Http\Middleware;

use App\Models\User;
use App\Models\TempLog;
use Inertia\Middleware;
use App\Models\Category;
use Tightenco\Ziggy\Ziggy;
use App\Models\Organization;
use Illuminate\Http\Request;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    public function version(Request $request)
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function share(Request $request)
    {
        // Set $temp_log to entry TempLog::where('user_id', auth()->user()->id)->where('clock_out', null)->first(), but order by created_at desc, and join with subcategories table by subcategory_id and then categories table by category_id
        $temp_log = TempLog::where('user_id', auth()->user()->id)->where('clock_out', null)->orderBy('created_at', 'desc')->first();
        if ($temp_log) {
            $temp_log->subcategory = $temp_log->subcategory()->first();
            // Set $temp_log->subcategory->category to entry Category::where('id', $temp_log->subcategory->category_id)->first()
            $temp_log->subcategory->category = Category::where('id', $temp_log->subcategory->category_id)->first();
        }
        // Return sharable props...
        return array_merge(parent::share($request), [
            'auth' => [
                'user' => $request->user(),
                // If user is logged in, set 'organizations' all organizations the user has subscribed to, otherwise set 'organizations' to null. Sort the organizations by name.
                'organizations' =>  auth()->user() ? Organization::whereHas('subscribers', function ($query) {
                    // Get all organizations that the user is subscribed to (not invited).
                    $query->where('user_id', auth()->user()->id)->where('status', '!=', 'invited');
                })->orderBy('name')->get() : null,
                // Look up the name of the organization with the active_org_id
                'active_org_name' => auth()->user() ? Organization::where('id', auth()->user()->active_org_id)->first()->name : null,
                // Look up the clocked_in state of the user
                'clocked_in' => auth()->user() ? User::where('id', auth()->user()->id)->first()->clocked_in : null,
                // Set `clocked_in_at` based on the last clock_in time if available
                'clocked_in_at' => $temp_log !== null ? $temp_log->clock_in : null,
                'temp_log' => [
                    'row' => $temp_log,
                    // // Set 'active_category' to the category of the user's last temp log entry where clock_out is null, or null if there are no temp log entries.
                    // 'active_subcategory_id' => auth()->user() ? TempLog::where('user_id', auth()->user()->id)->where('clock_out', null)->first()->subcategory_id : null,
                ],
            ],
            'ziggy' => function () use ($request) {
                return array_merge((new Ziggy)->toArray(), [
                    'location' => $request->url(),
                ]);
            },
            'flash' => [
                'message' => session('message'),
            ],
            'app' => [
                'name' => config('app.name'),
            ],
            'laravelVersion' => app()->version(),
            'phpVersion' => phpversion(),
        ]);
    }
}
