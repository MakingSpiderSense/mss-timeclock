<?php

namespace App\Http\Middleware;

use App\Models\User;
use App\Models\TempLog;
use Inertia\Middleware;
use App\Models\Category;
use Tightenco\Ziggy\Ziggy;
use App\Models\Subcategory;
use App\Models\Organization;
use Illuminate\Http\Request;
use Carbon\Carbon;

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
        if (auth()->user()) {
            // Get the last user's temp log entry where clock_out is null, or null if there are no temp log entries.
            $temp_log = TempLog::where('user_id', auth()->user()->id)->where('clock_out', null)->orderBy('created_at', 'desc')->first();
            if ($temp_log) {
                $temp_log->subcategory = $temp_log->subcategory()->first();
                // Set $temp_log->subcategory->category to entry Category::where('id', $temp_log->subcategory->category_id)->first()
                $temp_log->subcategory->category = Category::where('id', $temp_log->subcategory->category_id)->first();
            }
        } else {
            $temp_log = null;
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
                'stats' => [
                    'hours_month_unpaid' => isset($hours_month_unpaid) ? $hours_month_unpaid : '',
                    'hours_today_current_org' => isset($hours_today_current_org) ? $hours_today_current_org : '',
                    'amount_earned_today_current_org' => isset($amount_earned_today_current_org) ? $amount_earned_today_current_org : '',
                    'amount_earned_today_current_org_tax' => isset($amount_earned_today_current_org_tax) ? $amount_earned_today_current_org_tax : '',
                    'hours_month_current_org' => isset($hours_month_current_org) ? $hours_month_current_org : '',
                    'hours_weekly_this_month_current_org' => isset($hours_weekly_this_month_current_org) ? $hours_weekly_this_month_current_org : '',
                    'amount_earned_month_current_org' => isset($amount_earned_month_current_org) ? $amount_earned_month_current_org : '',
                    'amount_earned_month_current_org_tax' => isset($amount_earned_month_current_org_tax) ? $amount_earned_month_current_org_tax : '',
                    'hours_today_combined_org' => isset($hours_today_combined_org) ? $hours_today_combined_org : '',
                    'amount_earned_today_combined_org' => isset($amount_earned_today_combined_org) ? $amount_earned_today_combined_org : '',
                    'amount_earned_today_combined_org_tax' => isset($amount_earned_today_combined_org_tax) ? $amount_earned_today_combined_org_tax : '',
                    'hours_month_paid_combined_org' => isset($hours_month_paid_combined_org) ? $hours_month_paid_combined_org : '',
                    'hours_month_combined_org' => isset($hours_month_combined_org) ? $hours_month_combined_org : '',
                    'hours_weekly_this_month_combined_org' => isset($hours_weekly_this_month_combined_org) ? $hours_weekly_this_month_combined_org : '',
                    'amount_earned_month_combined_org' => isset($amount_earned_month_combined_org) ? $amount_earned_month_combined_org : '',
                    'amount_earned_month_combined_org_tax' => isset($amount_earned_month_combined_org_tax) ? $amount_earned_month_combined_org_tax : '',
                    'rate_this_month_paid_combined_org' => isset($rate_this_month_paid_combined_org) ? $rate_this_month_paid_combined_org : '',
                    'rate_this_month_combined_org' => isset($rate_this_month_combined_org) ? $rate_this_month_combined_org : '',
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
