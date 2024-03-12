<?php

use App\Http\Controllers\CompetitorController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GfcController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/', function () {
        return redirect('/dashboard');
    });

    /* Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard'); */
    Route::get('/dashboard', function () {
        return redirect('gasfriocalor/dashboard');
    });
    /* Route::post('/gasfriocalor', [DashboardController::class, 'dashboard'])->name('dashboard.dates'); */

    Route::prefix('gasfriocalor')->group(function () {
    Route::get('/dashboard', [GfcController::class, 'dashboard'])->name('gfc.dashboard');
    Route::get('/mejores-productos', [GfcController::class, 'bestProducts'])->name('gfc.bestproducts');
    Route::post('/mejores-productos/rango-fechas', [GfcController::class, 'bestProducts'])->name('gfc.bestproducts.dates');
    Route::get('/monitor-precios', [GfcController::class, 'monPrice'])->name('gfc.monprice');

    Route::get('datatable/monitor-precios', [GfcController::class, 'datatable'])->name('gfc.datatable.monprice');

    Route::get('competidor/nuevo', [CompetitorController::class, 'create'])->name('gfc.competidors.create');
    Route::post('competidor/nuevo', [CompetitorController::class, 'store'])->name('gfc.competidors.store');

    Route::get('producto/nuevo', [ProductController::class, 'create'])->name('gfc.products.create');
    Route::post('producto/nuevo', [ProductController::class, 'store'])->name('gfc.products.store');
});
});

Auth::routes();
Auth::routes(['register' => false]);

/* Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home'); */
