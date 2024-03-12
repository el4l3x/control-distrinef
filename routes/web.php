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

        Route::get('/monitor-precios/producto/crear', [GfcController::class, 'productCreate'])->name('gfc.monprice.product.create');
        Route::get('/monitor-precios/competidor/crear', [GfcController::class, 'competidorCreate'])->name('gfc.monprice.competidor.create');

        Route::post('/competidor', [CompetitorController::class,  'store'])->name('gfc.competidor.store');
        Route::post('/producto', [ProductController::class,  'store'])->name('gfc.product.store');
    });
});

Auth::routes();
Auth::routes(['register' => false]);

/* Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home'); */
