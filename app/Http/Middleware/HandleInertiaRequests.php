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
            $user = auth()->user();
            $userId = auth()->user()->id;
            // Get the last user's temp log entry where clock_out is null, or null if there are no temp log entries.
            $temp_log = TempLog::where('user_id', auth()->user()->id)->where('clock_out', null)->orderBy('created_at', 'desc')->first();
            if ($temp_log) {
                $temp_log->subcategory = $temp_log->subcategory()->first();
                // Set $temp_log->subcategory->category to entry Category::where('id', $temp_log->subcategory->category_id)->first()
                $temp_log->subcategory->category = Category::where('id', $temp_log->subcategory->category_id)->first();
            }
            // Calculate Stats from all of the user's temp_logs
            $temp_logs = TempLog::where('user_id', $userId)->get();
            $all_logs = [];
            $hours_month_unpaid = 0;
            $hours_today_current_org = 0;
            $amount_earned_today_current_org = 0;
            $hours_month_current_org = 0;
            $amount_earned_month_current_org = 0;
            $hours_today_combined_org = 0;
            $amount_earned_today_combined_org = 0;
            $hours_month_paid_combined_org = 0;
            $hours_month_combined_org = 0;
            $amount_earned_month_combined_org = 0;
            $time_zone = $user->time_zone ? $user->time_zone : 'UTC';
            $weeks_so_far = round(date('j') / 7, 2);
            $simple_tax_rate = $user->simple_tax_rate;
            // Loop through the temp_logs and build an array of objects with the data we need
            foreach ($temp_logs as $tempLog) {
                $subcategory = Subcategory::find($tempLog->subcategory_id); // get the subcategory for the temp_log
                $category = Category::find($subcategory->category_id); // get the category for the subcategory
                $org_name = Organization::find($category->org_id)->name; // get the org name for the category
                // Convert dates from UTC to user's time zone
                $clock_in_tz = Carbon::parse($tempLog->clock_in)->setTimezone($time_zone);
                $todays_date = Carbon::now()->setTimezone($time_zone)->format('Y-m-d');
                $clock_in_time = $clock_in_tz->format('Y-m-d H:i:s');
                $clock_in_date = $clock_in_tz->format('Y-m-d');
                $isToday = $clock_in_date == $todays_date;
                $isActiveOrg = $category->org_id == $user->active_org_id;
                $isPaid = $org_name != 'Unpaid';
                // Determine the rate for the category
                $rate = $user->categories()->where('user_id', $userId)->where('category_id', $category->id)->value('rate');
                if (!$rate && $isPaid) {
                    $rate = $user->subscriptions()->where('user_id', $userId)->where('org_id', $category->org_id)->value('rate');
                }
                if (!$rate && $isPaid) {
                    $rate = User::find($userId)->global_rate;
                }
                // Calculate the minutes for the entry
                if ($tempLog->clock_out != null) {
                    $minutes = $tempLog->minutes;
                } else {
                    // Set minutes to the difference between the time now and clock_in in minutes
                    $minutes = round((time() - strtotime($tempLog->clock_in)) / 60);
                }
                // Calculate the amount earned for the category
                $amountEarnedForCategory = ($minutes / 60) * $rate;
                $amountEarnedForCategory = round($amountEarnedForCategory, 2);
                // Calculate the amount earned for the category tax
                $amountEarnedForCategoryTax = $amountEarnedForCategory * User::find($userId)->simple_tax_rate;
                $amountEarnedForCategoryTax = round($amountEarnedForCategoryTax, 2);
                // If the category's org_name is 'Unpaid', add the minutes to the hours_month_unpaid variable
                if ($org_name == 'Unpaid') {
                    $hours_month_unpaid += $minutes;
                }
                // If active org and it's from today...
                if ($isActiveOrg && $isToday) {
                    $hours_today_current_org += $minutes;
                    $amount_earned_today_current_org += $amountEarnedForCategory;
                }
                // If active org and it's from any time this month...
                if ($isActiveOrg) {
                    $hours_month_current_org += $minutes;
                    $amount_earned_month_current_org += $amountEarnedForCategory;
                }
                // If it's from today...
                if ($isToday) {
                    $hours_today_combined_org += $minutes;
                    $amount_earned_today_combined_org += $amountEarnedForCategory;
                }
                // If paid hours and it's from any time this month...
                if ($isPaid) {
                    $hours_month_paid_combined_org += $minutes;
                }
                // If it's from any time this month...
                $hours_month_combined_org += $minutes;
                $amount_earned_month_combined_org += $amountEarnedForCategory;
                // Create an object for the current temp_log and add it to the all_logs array
                $result = (object) [
                    'clock_in_adjusted' => $clock_in_time,
                    'amount_earned_for_category' => $amountEarnedForCategory,
                    'org_name' => $org_name,
                    'minutes' => $minutes,
                    'rate_determined_for_category' => $rate,
                    'org_id' => $category->org_id,
                    'id' => $tempLog->id,
                    'active_org_id' => $user->active_org_id,
                    'clock_in' => $tempLog->clock_in,
                    'user_id' => $tempLog->user_id,
                    'subcategory_id' => $tempLog->subcategory_id,
                    'category_id' => $category->id,
                    'category_name' => $category->name,
                    'rate_determined_for_category' => $rate,
                    'amount_earned_for_category_tax' => $amountEarnedForCategoryTax,
                ];
                $all_logs[] = $result;
            }
            // Stat calculations continued...
            $hours_month_unpaid = round($hours_month_unpaid / 60, 1);
            $hours_today_current_org = round($hours_today_current_org / 60, 1);
            $amount_earned_today_current_org = round($amount_earned_today_current_org, 2);
            $amount_earned_today_current_org_tax = round($amount_earned_today_current_org * $simple_tax_rate, 2);
            $hours_month_current_org = round($hours_month_current_org / 60, 1);
            $hours_weekly_this_month_current_org = round($hours_month_current_org / $weeks_so_far, 1);
            $amount_earned_month_current_org = round($amount_earned_month_current_org, 2);
            $amount_earned_month_current_org_tax = round($amount_earned_month_current_org * $simple_tax_rate, 2);
            $hours_today_combined_org = round($hours_today_combined_org / 60, 1);
            $amount_earned_today_combined_org = round($amount_earned_today_combined_org, 2);
            $amount_earned_today_combined_org_tax = round($amount_earned_today_combined_org * $simple_tax_rate, 2);
            $hours_month_paid_combined_org = round($hours_month_paid_combined_org / 60, 1);
            $hours_month_combined_org = round($hours_month_combined_org / 60, 1);
            $hours_weekly_this_month_combined_org = round($hours_month_combined_org / $weeks_so_far, 1);
            $amount_earned_month_combined_org = round($amount_earned_month_combined_org, 2);
            $amount_earned_month_combined_org_tax = round($amount_earned_month_combined_org * $simple_tax_rate, 2);
            $rate_this_month_paid_combined_org = round($amount_earned_month_combined_org / $hours_month_paid_combined_org, 2);
            $rate_this_month_combined_org = round($amount_earned_month_combined_org / $hours_month_combined_org, 2);
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
                    'amount_earned_month_current_org_tax' => isset($amount_earned_month_current_org_tax) ? $amount_earned_month_current_org_tax : '',
                    'hours_today_combined_org' => isset($hours_today_combined_org) ? $hours_today_combined_org : '',
                    'amount_earned_today_combined_org' => isset($amount_earned_today_combined_org) ? $amount_earned_today_combined_org : '',
                    'amount_earned_today_combined_org_tax' => isset($amount_earned_today_combined_org_tax) ? $amount_earned_today_combined_org_tax : '',
                    'hours_month_paid_combined_org' => isset($hours_month_paid_combined_org) ? $hours_month_paid_combined_org : '',
                    'hours_month_combined_org' => isset($hours_month_combined_org) ? $hours_month_combined_org : '',
                    'hours_weekly_this_month_combined_org' => isset($hours_weekly_this_month_combined_org) ? $hours_weekly_this_month_combined_org : '',
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
