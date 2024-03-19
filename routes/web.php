<?php

use App\Http\Controllers\CompetitorController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GfcController;
use App\Http\Controllers\PrivadoController;
use App\Http\Controllers\ProductController;
use App\Models\Product;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

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
    Route::get('datatable/mejores-productos', [GfcController::class, 'datatableMejoresProductos'])->name('gfc.datatable.bestproducts');
    Route::get('datatable/mejores-aires', [GfcController::class, 'datatableMejoresAires'])->name('gfc.datatable.bestaires');
    Route::get('datatable/mejores-calderas', [GfcController::class, 'datatableMejoresCalderas'])->name('gfc.datatable.bestcalderas');
    Route::get('datatable/mejores-aerotermia', [GfcController::class, 'datatableMejoresAerotermia'])->name('gfc.datatable.bestaerotermia');
    Route::get('datatable/mejores-ventilacion', [GfcController::class, 'datatableMejoresVentilacion'])->name('gfc.datatable.bestventilacion');
    Route::get('datatable/mejores-caletadoresgas', [GfcController::class, 'datatableMejoresCaletadoresgas'])->name('gfc.datatable.bestcaletadoresgas');
    Route::get('datatable/mejores-termoselectricos', [GfcController::class, 'datatableMejoresTermoselectricos'])->name('gfc.datatable.besttermoselectricos');
    Route::get('datatable/mejores-superventas', [GfcController::class, 'datatableMejoresSuperventas'])->name('gfc.datatable.bestsuperventas');

    Route::get('competidor/nuevo', [CompetitorController::class, 'create'])->name('gfc.competidors.create');
    Route::post('competidor/nuevo', [CompetitorController::class, 'store'])->name('gfc.competidors.store');

    Route::get('producto/nuevo', [ProductController::class, 'create'])->name('gfc.products.create');
    Route::post('producto/nuevo', [ProductController::class, 'store'])->name('gfc.products.store');
    Route::delete('product/{product}', [ProductController::class, 'destroy'])->name('gfc.products.delete');

    Route::get('cambio-precios', [PrivadoController::class, 'cambioPrecios'])->name('gfc.privado.cambio-precios');
    Route::any('desbloquear-pedidos', [PrivadoController::class, 'desbloquearPedidos'])->name('gfc.privado.desbloquear-pedidos');
    /* Route::get('descargar-excels', [PrivadoController::class, 'descargarExcels'])->name('gfc.privado.descargar-excels'); */
    Route::any('descargar-excels', [PrivadoController::class, 'descargarExcels'])->name('gfc.privado.descargar-excels');
    Route::get('upload-dtocompra', [PrivadoController::class, 'uploadDtocompra'])->name('gfc.privado.upload_dtocompra');
    Route::any('consulta-stock', [PrivadoController::class, 'consultaStockNetosEditor'])->name('gfc.privado.consulta_stock-netos_editor');
    Route::any('datatable/consulta-stock', function (Request $request) {
       include(app_path() . '/privado/controlador.php'); 
    });

    /* Route::any('test', function (Request $request) {
       return view('gfc.privado.cambio-precios', compact('request'));
       include(app_path() . '/test.php');
    })->name('gfc.test'); */

});
});

Route::get('/monitor/scrap', function () {
    $web = new \Spekulatius\PHPScraper\PHPScraper;
    $products = Product::with('competidor')->get();

    foreach ($products as $key => $value) {
        foreach ($value->competidor as $key => $data) {
            try {
                $web->go($data->pivot->url);
                $string = $web->filter($data->filtro)->text();
                $string = Str::remove('€', $string);
                $string = Str::replace('.', '', $string);
                $string = Str::replace(',', '.', $string);
                $price = floatval($string);

                $value->competidor()->updateExistingPivot($data->id, [
                    'precio' => $price,
                ]);
            } catch (\Throwable $th) {
                return $th->getMessage();
                /* Log::info("------------ Fallo el scrap ------------");
                Log::info($th->getMessage()); */
            }
        }        
    }
});

Auth::routes();
Auth::routes(['register' => false]);

/* Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home'); */
