<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class MonthOverviewController extends Controller
{
	public function __construct()
	{
		// Force the user to be logged in to access these functions. This will redirect them to the login page if they are not.
		$this->middleware('auth');
	}

	/**
	 * Get current month overview by subcategory and category
     *
	 * Returns time log totals for subcategories and categories from the temp_logs table.
	 */
	public function index()
	{
		$userId = auth()->user()->id;

		// Subcategory total
		$subcategoryData = DB::table('temp_logs')
			->join('subcategories', 'temp_logs.subcategory_id', '=', 'subcategories.id')
			->join('categories', 'subcategories.category_id', '=', 'categories.id')
			->join('organizations', 'categories.org_id', '=', 'organizations.id')
			->select(
				'subcategories.id as subcategory_id',
				'organizations.name as organization_name',
				'categories.name as category_name',
				'subcategories.name as subcategory_name',
				DB::raw('SUM(temp_logs.minutes) as total_minutes'),
				DB::raw('FORMAT(SUM(temp_logs.minutes)/60, 2) as total_hours')
			)
			->where('temp_logs.user_id', $userId)
			->groupBy('subcategories.id', 'subcategories.name', 'categories.name', 'organizations.name')
			->orderBy('organizations.name')
			->orderBy('categories.name')
			->orderBy('subcategories.name')
			->get();

		// Category total
		$categoryData = DB::table('temp_logs')
			->join('subcategories', 'temp_logs.subcategory_id', '=', 'subcategories.id')
			->join('categories', 'subcategories.category_id', '=', 'categories.id')
			->join('organizations', 'categories.org_id', '=', 'organizations.id')
			->select(
				'categories.id as category_id',
				'organizations.name as organization_name',
				'categories.name as category_name',
				DB::raw('SUM(temp_logs.minutes) as total_minutes'),
				DB::raw('FORMAT(SUM(temp_logs.minutes)/60, 2) as total_hours')
			)
			->where('temp_logs.user_id', $userId)
			->groupBy('categories.id', 'categories.name', 'organizations.name')
			->orderBy('organizations.name')
			->orderBy('categories.name')
			->get();

		return inertia('Reports/MonthOverview', [
			'subcategoryData' => $subcategoryData,
			'categoryData' => $categoryData,
		]);
	}
}
