<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\TempLog;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Organization;
use Illuminate\Http\Request;

class StatsController extends Controller
{
    public function __construct()
    {
        // Force the user to be logged in to access these functions. This will redirect them to the login page if they are not.
        $this->middleware('auth');
    }
    
    // Return all stats
    public function index()
    {
        // Calculate Stats from all of the user's temp_logs
        $user = auth()->user();
        $userId = auth()->user()->id;
        $temp_logs = TempLog::where('user_id', $userId)->get();
        $all_logs = [];
        $hours_month_unpaid = 0;
        $hours_today_current_org = 0;
        $amount_earned_today_current_org = 0;
        $hours_month_current_org = 0;
        $amount_earned_month_current_org = 0;
        $hours_today_combined_org = 0;
        $amount_earned_today_combined_org = 0;
        $hours_month_total_work_combined_org = 0;
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
                $hours_month_total_work_combined_org += $minutes;
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
        $hours_month_unpaid = round($hours_month_unpaid / 60, 2);
        $hours_today_current_org = round($hours_today_current_org / 60, 2);
        $amount_earned_today_current_org = round($amount_earned_today_current_org, 2);
        $amount_earned_today_current_org_tax = round($amount_earned_today_current_org * $simple_tax_rate, 2);
        $hours_month_current_org = round($hours_month_current_org / 60, 2);
        $hours_weekly_this_month_current_org = round($hours_month_current_org / $weeks_so_far, 2);
        $amount_earned_month_current_org = round($amount_earned_month_current_org, 2);
        $amount_earned_month_current_org_tax = round($amount_earned_month_current_org * $simple_tax_rate, 2);
        $hours_today_combined_org = round($hours_today_combined_org / 60, 2);
        $amount_earned_today_combined_org = round($amount_earned_today_combined_org, 2);
        $amount_earned_today_combined_org_tax = round($amount_earned_today_combined_org * $simple_tax_rate, 2);
        $hours_month_total_work_combined_org = round($hours_month_total_work_combined_org / 60, 2);
        $hours_month_combined_org = round($hours_month_combined_org / 60, 2);
        $hours_weekly_this_month_combined_org = round($hours_month_combined_org / $weeks_so_far, 2);
        $amount_earned_month_combined_org = round($amount_earned_month_combined_org, 2);
        $amount_earned_month_combined_org_tax = round($amount_earned_month_combined_org * $simple_tax_rate, 2);
        $rate_this_month_work_combined_org = round($amount_earned_month_combined_org / $hours_month_total_work_combined_org, 2);
        // Return the data as an object
        return (object) [
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
            'hours_month_total_work_combined_org' => isset($hours_month_total_work_combined_org) ? $hours_month_total_work_combined_org : '',
            'hours_month_combined_org' => isset($hours_month_combined_org) ? $hours_month_combined_org : '',
            'hours_weekly_this_month_combined_org' => isset($hours_weekly_this_month_combined_org) ? $hours_weekly_this_month_combined_org : '',
            'amount_earned_month_combined_org' => isset($amount_earned_month_combined_org) ? $amount_earned_month_combined_org : '',
            'amount_earned_month_combined_org_tax' => isset($amount_earned_month_combined_org_tax) ? $amount_earned_month_combined_org_tax : '',
            'rate_this_month_work_combined_org' => isset($rate_this_month_work_combined_org) ? $rate_this_month_work_combined_org : '',
        ];
    }
}
