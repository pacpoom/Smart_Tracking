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
use App\Http\Controllers\ContainerTransactionController;
use App\Http\Controllers\ContainerShipOutController;
use App\Http\Controllers\ContainerYardDashboardController;
use App\Http\Controllers\ContainerTackingController;
use App\Http\Controllers\DisplayDashboardController;
use App\Http\Controllers\ContainerPullingPlanController;
use App\Http\Controllers\ContainerReturnController;
use App\Http\Controllers\ContainerExchangeController;
use App\Http\Controllers\ContainerOpenReturnCyController;
use App\Http\Controllers\ContainerReturnCyController;
use App\Http\Controllers\PackingListController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\PfepController;
use App\Http\Controllers\BomController;
use App\Http\Controllers\FileUploadController;
use App\Http\Controllers\WarehouseStockController;
use App\Http\Controllers\ProductionPlanController;
use App\Http\Controllers\MonitoringPlanController;
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
    // Route for printing pulling plan report
    Route::get('container-pulling-plans/report', [ContainerPullingPlanController::class, 'printReport'])->name('container-pulling-plans.report');
    Route::put('container-pulling-plans/{containerPullingPlan}/pick', [ContainerPullingPlanController::class, 'pick'])->name('container-pulling-plans.pick');
    Route::get('container-exchange/photo/{photo}', [ContainerExchangeController::class, 'showPhoto'])->name('container-exchange.showPhoto');
    // ✅ CHANGED: Corrected the route name to be plural
    Route::get('container-stock/by-current', [ContainerStockController::class, 'byCurrent'])->name('container-stocks.by-current');

    Route::get('monitoring-plan', [MonitoringPlanController::class, 'index'])->name('monitoring-plan.index');
    Route::post('monitoring-plan/update', [MonitoringPlanController::class, 'update'])->name('monitoring-plan.update');
    Route::get('monitoring-plan/export', [MonitoringPlanController::class, 'exportCsv'])->name('monitoring-plan.exportCsv');
   
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
    Route::delete('container-tacking.bulk-destroy', [ContainerTackingController::class, 'bulkDestroy'])->name('container-tacking.bulkDestroy');
    Route::delete('container-pulling-plans/bulk-destroy', [ContainerPullingPlanController::class, 'bulkDestroy'])->name('container-pulling-plans.bulkDestroy');

    // Download Routes
    Route::get('vendors/{vendor}/download', [VendorController::class, 'download'])->name('vendors.download');
    Route::get('part-requests/{partRequest}/download', [PartRequestController::class, 'download'])->name('part-requests.download');
    Route::get('part-requests/{partRequest}/download-delivery-document', [PartRequestController::class, 'downloadDeliveryDocument'])->name('part-requests.downloadDeliveryDocument');
    Route::get('container-tacking/{containerTacking}/download-photos', [ContainerTackingController::class, 'downloadPhotosAsZip'])->name('container-tacking.downloadPhotos');

    // Search Routes
    Route::get('parts/search', [PartController::class, 'search'])->name('parts.search');
    Route::get('containers/search', [ContainerController::class, 'search'])->name('containers.search');
    Route::get('yard-locations/search', [YardLocationController::class, 'search'])->name('yard-locations.search');
    Route::get('yard-locations/searchDock', [YardLocationController::class, 'searchDock'])->name('yard-locations.searchDock');
    Route::get('container-order-plans/search', [ContainerOrderPlanController::class, 'search'])->name('container-order-plans.search');
    Route::get('container-order-plans/search-stock', [ContainerOrderPlanController::class, 'searchStock'])->name('container-order-plans.searchStock');
    Route::get('container-order-plans/search-stock-pulling', [ContainerOrderPlanController::class, 'searchStockPulling'])->name('container-order-plans.searchStockPulling');
    Route::get('container-stocks/search', [ContainerStockController::class, 'search'])->name('container-stocks.search');
    Route::get('container-stocks/search-empty', [ContainerReturnController::class, 'search'])->name('container-stocks.search-empty');
    Route::get('materials/search', [MaterialController::class, 'search'])->name('materials.search');


    // Import & Export Routes
    Route::get('container-order-plans/template', [ContainerOrderPlanController::class, 'downloadTemplate'])->name('container-order-plans.template');
    Route::post('container-order-plans/import', [ContainerOrderPlanController::class, 'import'])->name('container-order-plans.import');
    Route::get('container-order-plans/export', [ContainerOrderPlanController::class, 'export'])->name('container-order-plans.export');
    Route::get('part-requests/export', [PartRequestController::class, 'export'])->name('part-requests.export');
    Route::get('container-stocks/export', [ContainerStockController::class, 'export'])->name('container-stocks.export');
    Route::get('container-transactions/export', [ContainerTransactionController::class, 'export'])->name('container-transactions.export');
    Route::get('warehouse-stock/export', [WarehouseStockController::class, 'export'])->name('warehouse-stock.export');
    Route::get('production-plans/export', [ProductionPlanController::class, 'export'])->name('production-plans.export');

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
    Route::resource('container-pulling-plans', ContainerPullingPlanController::class);
    Route::resource('container-tacking', ContainerTackingController::class)->except(['edit', 'update', 'show']);
    Route::resource('container-exchange', ContainerExchangeController::class)->only(['index', 'create', 'store', 'show']);
    Route::resource('materials', MaterialController::class);
    Route::resource('pfeps', PfepController::class);

    // AJAX route for fetching BOM details - MOVED HERE
    Route::get('/production-plans/get-bom', [ProductionPlanController::class, 'getBom'])->name('production-plans.getBom');
    Route::resource('production-plans', ProductionPlanController::class); 

    // ✅ ADD: Route for setting a PFEP as primary
    Route::post('pfeps/{pfep}/set-primary', [PfepController::class, 'setPrimary'])->name('pfeps.setPrimary');


    // File Upload Routes
    Route::get('files', [FileUploadController::class, 'index'])->name('files.index');
    Route::get('files/create', [FileUploadController::class, 'create'])->name('files.create');
    Route::post('files', [FileUploadController::class, 'store'])->name('files.store');
    Route::get('files/{file}/download', [FileUploadController::class, 'download'])->name('files.download');
    Route::delete('files/{file}', [FileUploadController::class, 'destroy'])->name('files.destroy');
    Route::get('files/{file}/edit', [FileUploadController::class, 'edit'])->name('files.edit');
    Route::put('files/{file}', [FileUploadController::class, 'update'])->name('files.update');


    // Stock Routes
    Route::get('stocks', [StockController::class, 'index'])->name('stocks.index');
    Route::get('warehouse-stock', [WarehouseStockController::class, 'index'])->name('warehouse-stock.index');
    Route::put('parts/{part}/stock/adjust', [StockController::class, 'adjust'])->name('stocks.adjust');
    Route::post('stocks/store-part', [StockController::class, 'storePartAndStock'])->name('stocks.storePartAndStock');

    // Container Receive Routes
    Route::get('container-receive/create', [ContainerReceiveController::class, 'create'])->name('container-receive.create');
    Route::post('container-receive', [ContainerReceiveController::class, 'store'])->name('container-receive.store');

    // Container Stock Route
    Route::get('container-stocks', [ContainerStockController::class, 'index'])->name('container-stocks.index');
    Route::get('container-transactions', [ContainerTransactionController::class, 'index'])->name('container-transactions.index');

    // Container Change Location Routes
    Route::get('container-change-location', [ContainerChangeLocationController::class, 'index'])->name('container-change-location.index');
    Route::put('container-change-location/{stock}', [ContainerChangeLocationController::class, 'update'])->name('container-change-location.update');
    Route::get('/packing-list', [PackingListController::class, 'index'])->name('packing-list.index');
    Route::get('packing-list/export', [PackingListController::class, 'export'])->name('packing-list.export');
    // Container Ship Out Routes
    Route::get('container-ship-out', [ContainerShipOutController::class, 'index'])->name('container-ship-out.index');
    Route::put('container-ship-out/{pullingPlan}', [ContainerShipOutController::class, 'shipOut'])->name('container-ship-out.shipOut');

    // Container Open Routes
    Route::get('container-open', [ContainerReturnCyController::class, 'index'])->name('container-open.index');
    Route::put('container-open/{pullingPlan}', [ContainerReturnCyController::class, 'shipOut'])->name('container-open.shipOut');

    // Container Yard Dashboard Route
    Route::get('container-yard/dashboard', [ContainerYardDashboardController::class, 'index'])->name('container-yard.dashboard');

    // Container Tacking Routes
    Route::get('container-tacking/create', [ContainerTackingController::class, 'create'])->name('container-tacking.create');
    Route::post('container-tacking', [ContainerTackingController::class, 'store'])->name('container-tacking.store');
    Route::get('container-tacking/photos/{photo}', [ContainerTackingController::class, 'showPhoto'])->name('container-tacking.photo.show');
    Route::get('display-dashboard', [App\Http\Controllers\DisplayDashboardController::class, 'index'])->name('display.dashboard');
    Route::post('container-tacking/{containerTacking}/add-photos', [ContainerTackingController::class, 'addPhotos'])->name('container-tacking.addPhotos');

    // Container Return Routes
    Route::get('container-return', [ContainerReturnController::class, 'index'])->name('container-return.index');
    Route::put('container-return/{stock}', [ContainerReturnController::class, 'returnContainer'])->name('container-return.return');

    // Container Exchange Routes
    Route::get('container-exchange/create', [ContainerExchangeController::class, 'create'])->name('container-exchange.create');
    Route::post('container-exchange', [ContainerExchangeController::class, 'store'])->name('container-exchange.store');
    Route::get('/bill-of-materials', [BomController::class, 'index'])->name('bom.index');
    Route::post('/bom/import', [BomController::class, 'import'])->name('bom.import');
    Route::get('/bom/template', [BomController::class, 'exportTemplate'])->name('bom.template');
    Route::resource('container-tacking', ContainerTackingController::class);
    Route::get('/container-open-return-cy', [ContainerOpenReturnCyController::class, 'index'])->name('container-open-return-cy.index');
    Route::post('/container-open-return-cy', [ContainerOpenReturnCyController::class, 'store'])->name('container-open-return-cy.store');
});

require __DIR__ . '/auth.php';