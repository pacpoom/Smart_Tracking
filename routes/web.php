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
use App\Http\Controllers\DashboardController; // เพิ่ม use statement นี้ไว้ด้านบน
use App\Http\Controllers\StockController;
use App\Http\Controllers\YardLocationController;
use App\Http\Controllers\ContainerController;
// Redirect root to login page
Route::get('/', function () {
    return redirect()->route('login');
});

// Dashboard route
Route::get('/dashboard', [DashboardController::class, 'index'])
->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    // Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy'); // ปกติไม่ค่อยมีปุ่มลบตัวเอง
    Route::put('/password', [ProfileController::class, 'updatePassword'])->name('password.update');


    // Bulk Delete Routes (Correct Syntax)
    Route::delete('roles/bulk-destroy', [RolePermissionController::class, 'bulkDestroy'])->name('roles.bulkDestroy');
    Route::delete('permissions/bulk-destroy', [PermissionController::class, 'bulkDestroy'])->name('permissions.bulkDestroy');
    Route::delete('users/bulk-destroy', [UserController::class, 'bulkDestroy'])->name('users.bulkDestroy');

    // Resource Routes
    Route::resource('roles', RolePermissionController::class);
    Route::resource('permissions', PermissionController::class);
    Route::resource('users', UserController::class);

    Route::resource('menus', MenuController::class);
    Route::delete('menus.bulkdestroy', [MenuController::class, 'bulkDestroy'])->name('menus.bulkDestroy');

    Route::delete('vendors.bulk-destroy', [VendorController::class, 'bulkDestroy'])->name('vendors.bulkDestroy');
    Route::resource('vendors', VendorController::class);
    // Download Route for Vendor Attachment
    Route::get('vendors/{vendor}/download', [VendorController::class, 'download'])->name('vendors.download');

    // Part Master Routes
    Route::delete('parts.bulkdestroy', [PartController::class, 'bulkDestroy'])->name('parts.bulkDestroy');
    Route::resource('parts', PartController::class);

    // Part Request Routes
    Route::get('part-requests/{partRequest}/download', [PartRequestController::class, 'download'])->name('part-requests.download');
    Route::resource('part-requests', PartRequestController::class)->only(['index', 'create', 'store', 'edit', 'update']);

     // Download Routes
    Route::get('vendors/{vendor}/download', [VendorController::class, 'download'])->name('vendors.download');
    Route::get('part-requests/{partRequest}/download', [PartRequestController::class, 'download'])->name('part-requests.download');
    // --- เพิ่มบรรทัดนี้เข้ามา ---
    Route::get('part-requests/{partRequest}/download-delivery-document', [PartRequestController::class, 'downloadDeliveryDocument'])->name('part-requests.downloadDeliveryDocument');
    // Export Route for Part Requests
    Route::get('part-requests/export', [PartRequestController::class, 'export'])->name('part-requests.export');
    // Search Route for Parts (for Select2 AJAX)
    Route::get('parts.search', [PartController::class, 'search'])->name('parts.search');

    // Stock Management Routes
    Route::get('stocks', [StockController::class, 'index'])->name('stocks.index');
    Route::put('parts/{part}/stock/adjust', [StockController::class, 'adjust'])->name('stocks.adjust');
    
    // Route for creating a new Part and Stock
    Route::post('stocks/store-part', [StockController::class, 'storePartAndStock'])->name('stocks.storePartAndStock');

    // Route for updating profile photo
    Route::post('/profile/photo', [ProfileController::class, 'updatePhoto'])->name('profile.photo.update');

    // Route for serving profile photo
    Route::get('/user/profile-photo', [ProfileController::class, 'showPhoto'])->name('profile.photo.show');

    Route::delete('yard-locations/bulk-destroy', [YardLocationController::class, 'bulkDestroy'])->name('yard-locations.bulkDestroy');
    Route::resource('yard-locations', YardLocationController::class);

    // Container Routes
    Route::delete('containers/bulk-destroy', [ContainerController::class, 'bulkDestroy'])->name('containers.bulkDestroy');
    Route::resource('containers', ContainerController::class);
});


require __DIR__.'/auth.php';
