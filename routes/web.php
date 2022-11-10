<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use App\Http\Controllers\ProfilesController;
use App\Http\Controllers\ClockInOutController;
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

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Add route to 'placeholder' which simply returns the string 'Placeholder page'. 
Route::get('/placeholder', function () {
    return 'Placeholder page';
})->name('placeholder');

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

// Clock In/Out routes
Route::post('/clock-in', [ClockInOutController::class, 'clockIn'])->name('clock-in');

// Hourly Rates route
Route::get('/hourly-rates', function () {
    return Inertia::render('Rates');
})->middleware(['auth', 'verified'])->name('hourly-rates');

require __DIR__.'/auth.php';

// Clear cache without CLI
Route::get('/clear-cache', function() {
    $exitCode = Artisan::call('config:clear');
    $exitCode = Artisan::call('cache:clear');
    $exitCode = Artisan::call('config:cache');
    return 'DONE'; // Return anything
});