<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RolePermissionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\PartController;
use App\Http\Controllers\PartRequestController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\YardLocationController;
use App\Http\Controllers\ContainerController;
use App\Http\Controllers\ContainerOrderPlanController;
use App\Http\Controllers\ContainerReceiveController;
use App\Http\Controllers\ContainerStockController;
use App\Http\Controllers\ContainerChangeLocationController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/password', [ProfileController::class, 'updatePassword'])->name('password.update');
    Route::post('/profile/photo', [ProfileController::class, 'updatePhoto'])->name('profile.photo.update');
    Route::get('/user/profile-photo', [ProfileController::class, 'showPhoto'])->name('profile.photo.show');

    // Bulk Delete Routes
    Route::delete('roles/bulk-destroy', [RolePermissionController::class, 'bulkDestroy'])->name('roles.bulkDestroy');
    Route::delete('permissions/bulk-destroy', [PermissionController::class, 'bulkDestroy'])->name('permissions.bulkDestroy');
    Route::delete('users/bulk-destroy', [UserController::class, 'bulkDestroy'])->name('users.bulkDestroy');
    Route::delete('menus/bulk-destroy', [MenuController::class, 'bulkDestroy'])->name('menus.bulkDestroy');
    Route::delete('vendors/bulk-destroy', [VendorController::class, 'bulkDestroy'])->name('vendors.bulkDestroy');
    Route::delete('parts/bulk-destroy', [PartController::class, 'bulkDestroy'])->name('parts.bulkDestroy');
    Route::delete('container-order-plans/bulk-destroy', [ContainerOrderPlanController::class, 'bulkDestroy'])->name('container-order-plans.bulkDestroy');
    Route::delete('yard-locations/bulk-destroy', [YardLocationController::class, 'bulkDestroy'])->name('yard-locations.bulkDestroy');
    Route::delete('containers/bulk-destroy', [ContainerController::class, 'bulkDestroy'])->name('containers.bulkDestroy');

    // Download Routes
    Route::get('vendors/{vendor}/download', [VendorController::class, 'download'])->name('vendors.download');
    Route::get('part-requests/{partRequest}/download', [PartRequestController::class, 'download'])->name('part-requests.download');
    Route::get('part-requests/{partRequest}/download-delivery-document', [PartRequestController::class, 'downloadDeliveryDocument'])->name('part-requests.downloadDeliveryDocument');

    // Search Routes
    Route::get('parts/search', [PartController::class, 'search'])->name('parts.search');
    Route::get('containers/search', [ContainerController::class, 'search'])->name('containers.search');
    Route::get('yard-locations/search', [YardLocationController::class, 'search'])->name('yard-locations.search');

    // Import & Export Routes
    Route::get('container-order-plans/template', [ContainerOrderPlanController::class, 'downloadTemplate'])->name('container-order-plans.template');
    Route::post('container-order-plans/import', [ContainerOrderPlanController::class, 'import'])->name('container-order-plans.import');
    Route::get('container-order-plans/export', [ContainerOrderPlanController::class, 'export'])->name('container-order-plans.export');
    Route::get('part-requests/export', [PartRequestController::class, 'export'])->name('part-requests.export');
    Route::get('container-stocks/export', [ContainerStockController::class, 'export'])->name('container-stocks.export');


    // Resource Routes
    Route::resource('roles', RolePermissionController::class);
    Route::resource('permissions', PermissionController::class);
    Route::resource('users', UserController::class);
    Route::resource('menus', MenuController::class);
    Route::resource('vendors', VendorController::class);
    Route::resource('parts', PartController::class);
    Route::resource('part-requests', PartRequestController::class)->except(['show', 'destroy']);
    Route::resource('yard-locations', YardLocationController::class)->except(['show']);
    Route::resource('containers', ContainerController::class)->except(['show']);
    Route::resource('container-order-plans', ContainerOrderPlanController::class)->except(['show']);
    
    // Stock Routes
    Route::get('stocks', [StockController::class, 'index'])->name('stocks.index');
    Route::put('parts/{part}/stock/adjust', [StockController::class, 'adjust'])->name('stocks.adjust');
    Route::post('stocks/store-part', [StockController::class, 'storePartAndStock'])->name('stocks.storePartAndStock');

    // Container Receive Routes
    Route::get('container-receive/create', [ContainerReceiveController::class, 'create'])->name('container-receive.create');
    Route::post('container-receive', [ContainerReceiveController::class, 'store'])->name('container-receive.store');

    // Container Stock Route
    Route::get('container-stocks', [ContainerStockController::class, 'index'])->name('container-stocks.index');

    // Container Change Location Routes
    Route::get('container-change-location', [ContainerChangeLocationController::class, 'index'])->name('container-change-location.index');
    Route::put('container-change-location/{stock}', [ContainerChangeLocationController::class, 'update'])->name('container-change-location.update');
});

require __DIR__.'/auth.php';
