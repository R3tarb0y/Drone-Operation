<?php

use App\Http\Controllers\AssetController;
use App\Http\Controllers\EstimationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SparepartController;
use App\Http\Controllers\SparepartTransactionController;
use App\Http\Controllers\RequestController;
use App\Http\Controllers\ReceiveController;
use App\Http\Controllers\TransferController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RealisasiController;
use App\Http\Controllers\BudgetController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\NotificationController;
use App\Models\Budget;
use App\Models\Estimation;
use App\Models\Realisasi;
use Illuminate\Support\Facades\Route;

// Existing Routes...
Route::get('/', function () {
    return view('auth.login');
})->middleware('guest');    

Route::middleware(['auth', 'verified'])->get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/asset', [AssetController::class, 'index'])->name('asset.index');
    Route::get('asset/{id_asset}/history', [AssetController::class, 'showHistory'])->name('asset.history');
    
});

// New Route for the Asset Page
Route::get('/inventory/assets', function () {
    return view('inventory.asset');  // Assuming the file is located in resources/views/inventory/asset.blade.php
})->middleware(['auth', 'verified'])->name('inventory.assets');

Route::get('/sparepart', [SparepartController::class, 'index'])->name('sparepart.index');
Route::post('/spareparts/add', [SparepartController::class, 'addSparepart'])->name('sparepart.add');


Route::prefix('sparepart/transaction')->name('sparepart.transaction.')->group(function () {
    Route::get('/', [SparepartTransactionController::class, 'index'])->name('index');  // Index route for all transactions
    Route::get('/in', [SparepartTransactionController::class, 'showInTransactions'])->name('in');
    Route::post('/in', [SparepartTransactionController::class, 'addSparepartIn'])->name('in.submit');

    Route::get('/out', [SparepartTransactionController::class, 'showOutTransactions'])->name('out');
    Route::post('/out', [SparepartTransactionController::class, 'subtractSparepartOut'])->name('out.submit');
});

Route::post('/spareparts/add-stock', [SparepartTransactionController::class, 'addStockToWarehouse'])->name('spareparts.add-stock');
Route::post('/spareparts/subtract-stock', [SparepartTransactionController::class, 'subtractStockFromWarehouse'])->name('spareparts.subtract-stock');


Route::get('asset/{id_asset}/history', [AssetController::class, 'showHistory'])->name('asset.history');
Route::get('/assets/{id_asset}/edit', [AssetController::class, 'edit'])->name('asset.edit');
Route::put('/assets/{id_asset}', [AssetController::class, 'update'])->name('asset.update');


Route::get('/request', [RequestController::class, 'index'])->name('request.index');
Route::post('/request', [RequestController::class, 'store'])->name('request.store');


Route::patch('/request/{id}/approve', [RequestController::class, 'approve'])->name('request.approve');

Route::patch('/request/{id}/update-status', [RequestController::class, 'updateStatus'])->name('request.updateStatus');

Route::get('/receive', [ReceiveController::class, 'index'])->name('receive.index');
Route::post('/receive/{id}/update-status', [ReceiveController::class, 'updateReceiveStatus'])->name('receive.updateStatus');
Route::get('/receive/export-csv', [ReceiveController::class, 'exportCsv'])->name('receive.exportCsv');
Route::get('/request/{id}/approve', [RequestController::class, 'approve'])->name('receive.approve');
// Add this route for the storeApproval method
Route::post('/receive/{id}/approve', [ReceiveController::class, 'storeApproval'])->name('receive.storeApproval');

Route::get('transfer', [TransferController::class, 'index'])->name('transfer.index');
Route::get('transfer/create', [TransferController::class, 'create'])->name('transfer.create');
Route::post('transfer', [TransferController::class, 'store'])->name('transfer.store');
Route::post('transfer/{id}/approve', [TransferController::class, 'approve'])->name('transfer.approve');
Route::get('transfer/stok', [TransferController::class, 'getStok'])->name('transfer.getStok');
Route::get('/transfer/export-csv', [TransferController::class, 'exportCsv'])->name('transfer.exportCsv');

Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
Route::get('/reports/create', [ReportController::class, 'create'])->name('reports.create');
Route::post('/reports', [ReportController::class, 'store'])->name('reports.store');
Route::get('/reports/export-csv', [ReportController::class, 'exportCsv'])->name('reports.exportCsv');

Route::get('/estimations', [EstimationController::class, 'index'])->name('estimations.index');
Route::get('/estimations/create', [EstimationController::class, 'create'])->name('estimations.create');
Route::post('/estimations', [EstimationController::class, 'store'])->name('estimations.store');
Route::get('/estimations/get-spareparts', [EstimationController::class, 'getSpareparts']);
Route::get('/estimations/get-data-by-report', [EstimationController::class, 'getDataByReport']);
Route::get('/estimations/export-csv', [EstimationController::class, 'exportCsv'])->name('estimations.exportCsv');

Route::get('/estimations/get-price-quantity', [EstimationController::class, 'getPriceQuantity']);
Route::patch('/estimations/{id}/update-status', [EstimationController::class, 'updateStatus'])->name('estimations.updateStatus');
// Rute untuk memperbarui spareparts dalam modal
Route::patch('/estimations/{id}/update-spareparts', [EstimationController::class, 'updateSpareparts'])->name('estimations.updateSpareparts');
Route::patch('/estimation/{id}/update', [EstimationController::class, 'updateSpareparts']);
Route::post('/estimations/{estimationId}/remove-sparepart', [EstimationController::class, 'removeSparepart']);
Route::post('/estimations/{id}/approve', [EstimationController::class, 'approveEstimation'])->name('estimations.approve');
Route::get('/spareparts/search', [SparepartController::class, 'search']);
Route::get('/requests/export-csv', [RequestController::class, 'exportCSV'])->name('request.export.csv');

Route::get('/budget', [BudgetController::class, 'index'])->name('budget.index');
Route::get('/budget/create', [BudgetController::class, 'create'])->name('budget.create');
Route::post('/budget', [BudgetController::class, 'store'])->name('budget.store');
Route::get('/budget/edit/{id}', [BudgetController::class, 'edit'])->name('budget.edit');
Route::put('/budget/{id}', [BudgetController::class, 'update'])->name('budget.update');  // Tambahkan ini
Route::delete('/budget', [BudgetController::class, 'destroy'])->name('budget.destroy');

Route::get('/notifications', [NotificationController::class, 'getNotifications']);
Route::resource('transfers', TransferController::class);
Route::resource('requests', RequestController::class);
Route::resource('receives', ReceiveController::class);
Route::resource('estimations', EstimationController::class);




Route::prefix('realisasi')->group(function () {
    Route::get('/', [RealisasiController::class, 'index'])->name('realisasi.index'); // Menampilkan daftar realisasi

});

Route::patch('realisasi/{id}/approve', [RealisasiController::class, 'approve'])->name('realisasi.approve');
Route::get('/realisasi/export-csv', [RealisasiController::class, 'exportCsv'])->name('realisasi.exportCsv');









require __DIR__.'/auth.php';
