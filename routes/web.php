<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FetchController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UpdateController;
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
    return redirect('/pulloutform');
    // return Inertia::render('DashboardBen', [
    //     'canLogin' => Route::has('login'),
    //     'canRegister' => Route::has('register'),
    //     'laravelVersion' => Application::VERSION,
    //     'phpVersion' => PHP_VERSION,
    // ]);
});

Route::get('/pulloutform', function () {
    return Inertia::render('Dashboard', [
            'canLogin' => Route::has('login'),
            'canRegister' => Route::has('register'),
            'laravelVersion' => Application::VERSION,
            'phpVersion' => PHP_VERSION,
        ]);
})->middleware(['auth', 'verified'])->name('pulloutform');

// Route::get('/Ben', function () {
//     return Inertia::render('DashboardBen');
// })->middleware(['auth', 'verified'])->name('dashboardben');

Route::get('/drafttransaction', function () {
    return Inertia::render('DraftTransaction');
})->middleware(['auth', 'verified'])->name('drafttransaction');

Route::get('/pullouttransactions', function () {
    return Inertia::render('PullOutTransactions');
})->middleware(['auth', 'verified'])->name('pullouttransactions');

Route::get('/myprofile', function () {
    return Inertia::render('MyProfile');
})->middleware(['auth', 'verified'])->name('myprofile');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get("/fetchCompany", [FetchController::class, 'fetchCompany']);
    Route::get("/fetchChain", [FetchController::class, 'fetchChain']);
    Route::get("/fetchChainName", [FetchController::class, 'fetchChainName']);
    Route::get("/fetchCompanyByUser", [FetchController::class, 'fetchCompanyByUser']);
    Route::get("/fetchChainByUser", [FetchController::class, 'fetchChainByUser']);
    Route::get("/fetchChainNameByUser", [FetchController::class, 'fetchChainNameByUser']);
    Route::get("/fetchUserRequestDraft", [FetchController::class, 'fetchUserRequestDraft']);
    Route::get("/fetchItemsNBFI", [FetchController::class, 'fetchItemsNBFI']);
    Route::get("/fetchItems", [FetchController::class, 'fetchItems']);
    Route::get("/fetchItemsBarcode", [FetchController::class, 'fetchItemsBarcode']);
    Route::get("/compareItemCode", [FetchController::class, 'compareItemCode']);
    Route::get("/fetchBrands", [FetchController::class, 'fetchBrands']);
    Route::get("/fetchSameItem", [FetchController::class, 'fetchSameItem']);
    Route::post("/savePullOutBranchRequest", [PostController::class, 'savePullOutBranchRequest']);
    Route::post("/savePullOutItemRequest", [PostController::class, 'savePullOutItemRequest']);
    Route::post("/updatePullOutBranchRequest", [UpdateController::class, 'updatePullOutBranchRequest']);
    Route::post("/updatePullOutItemRequest", [UpdateController::class, 'updatePullOutItemRequest']);
    Route::post("/upload", [PostController::class, 'upload']);
    Route::post("/deleteDraft", [PostController::class, 'deleteDraft']);
    Route::post("/deleteUserBranch", [PostController::class, 'deleteUserBranch']);
    Route::get("/fetchEditDraftBranch", [FetchController::class, 'fetchEditDraftBranch']);
    Route::get("/fetchEditDraftItem", [FetchController::class, 'fetchEditDraftItem']);
    Route::get("/fetchPromoBranchInfo", [FetchController::class, 'fetchPromoBranchInfo']);
    Route::get("/fetchUserRequestTransactionList", [FetchController::class, 'fetchUserRequestTransactionList']);
    Route::get("/fetchPullOutRequestItem", [FetchController::class, 'fetchPullOutRequestItem']);
    Route::get("/fetchImageBranchDoc", [FetchController::class, 'fetchImageBranchDoc']);
    Route::get("/fetchUserRequestTransactionListAdmin", [FetchController::class, 'fetchUserRequestTransactionListAdmin']);
    Route::get("/usersProfile", [FetchController::class, 'usersProfile']);
    Route::post("/postPromoUserBranch", [PostController::class, 'postPromoUserBranch']);
    Route::post("/deleteImage", [PostController::class, 'deleteImage']);

    Route::get("/testing", [UpdateController::class, 'testing']);
    Route::get("/deleteUsersNotVerified", [UpdateController::class, 'deleteUsersNotVerified']);


});

require __DIR__.'/auth.php';
