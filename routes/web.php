<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FetchController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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

Route::get('/Ric', function () {
    return Inertia::render('DashboardRic');
})->middleware(['auth', 'verified'])->name('dashboardric');

Route::get('/Ben', function () {
    return Inertia::render('DashboardBen');
})->middleware(['auth', 'verified'])->name('dashboardben');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get("/fetchCompany", [FetchController::class, 'fetchCompany']);
    Route::get("/fetchChain", [FetchController::class, 'fetchChain']);
    Route::get("/fetchChainName", [FetchController::class, 'fetchChainName']);
});

require __DIR__.'/auth.php';
