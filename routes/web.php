<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use App\Http\Controllers\RatesController;
use App\Http\Controllers\StatsController;
use App\Http\Controllers\PrivateController;
use App\Http\Controllers\TimeLogController;
use App\Http\Controllers\ProfilesController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\ClockInOutController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\OrganizationsController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

// Dashboard
Route::get('/dashboard',  [ClockInOutController::class, 'index'])->name('dashboard');
// Clock In/Out
Route::post('/clock-in-out', [ClockInOutController::class, 'clockInOut'])->name('clock-in-out');
// Cancel Clock In
Route::post('/cancel-clock-in', [ClockInOutController::class, 'cancelClockIn'])->name('cancel-clock-in');

// Add route to 'placeholder' which simply returns the string 'Placeholder page'. 
Route::get('/placeholder', function () {
    return 'Coming soon...';
})->middleware(['auth', 'verified'])->name('placeholder');

// Profile routes
Route::get('/profile', function () {
    return Inertia::render('Profile/Profile');
})->middleware(['auth', 'verified'])->name('profile');
// Edit Profile
Route::get('/profile/edit', function () {
    return Inertia::render('Profile/Edit');
})->middleware(['auth', 'verified'])->name('profile.edit');
// Update Profile
Route::post('/profile/update', [ProfilesController::class, 'update'])->middleware(['auth', 'verified'])->name('profile.update');

// Organization routes
Route::post('/organization', [OrganizationsController::class, 'store'])->name('organizations.store');
Route::post('/organization/invite', [OrganizationsController::class, 'invite'])->name('organizations.invite');
Route::get('/organization/invite-list', [OrganizationsController::class, 'show_invitations'])->name('organizations.invite-list');
Route::post('/organization/invite-accept/{org_id}', [OrganizationsController::class, 'accept_invitation'])->name('organizations.invite-accept');
Route::post('/organization/invite-decline/{org_id}', [OrganizationsController::class, 'decline_invitation'])->name('organizations.invite-decline');
Route::post('/organization/active/{org_id}', [OrganizationsController::class, 'set_active'])->name('organizations.active');

// Hourly Rates route
Route::get('/rates', [RatesController::class, 'index'])->name('rates');
Route::post('/rate/update', [RatesController::class, 'update'])->name('rate.update');

// Reports route
Route::get('/reports/time-log', [TimeLogController::class, 'index'])->name('reports.time-log');
Route::get('/reports/private', [PrivateController::class, 'index'])->name('reports.private');

// Stats route
Route::get('/stats', [StatsController::class, 'index'])->name('stats');

// Settings routes
Route::get('/settings', [SettingsController::class, 'index'])->name('settings');
Route::get('/settings/hidden-categories', [SettingsController::class, 'show_hidden_categories'])->name('settings.hidden-categories');
// Update stats view settings
Route::post('/settings/stats-view', [SettingsController::class, 'updateStatsView'])->name('settings.stats-view');

// SuperAdmin Dashboard
Route::get('/super-admin', [SuperAdminController::class, 'index'])->middleware(['auth', 'verified'])->name('super-admin');
Route::post('/super-admin/consolidate', [SuperAdminController::class, 'consolidateTimeLogs'])->middleware(['auth', 'verified'])->name('super-admin.consolidate');

require __DIR__.'/auth.php';

// Clear cache without CLI
Route::get('/clear-cache', function() {
    $exitCode = Artisan::call('config:clear');
    $exitCode = Artisan::call('cache:clear');
    $exitCode = Artisan::call('config:cache');
    return 'DONE'; // Return anything
});