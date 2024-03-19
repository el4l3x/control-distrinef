<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PrivadoController extends Controller
{
    public function cambioPrecios() {
        include(app_path() . '/privado/cambio-precios.php');
    }
    
    public function desbloquearPedidos() {
        include(app_path() . '/privado/desbloquear-pedidos.php');
    }
    
    public function descargarExcels(Request $request) {
        return view('gfc.privado.cambio-precios', compact('request'));
        /* include(app_path() . '/privado/descargar-excels.php'); */
    }
    
    public function uploadDtocompra() {
        include(app_path() . '/privado/upload_dtocompra.php.php');
    }
    
    public function consultaStockNetosEditor() {
        include(app_path() . '/privado/consulta_stock-netos_editor.php');
    }
}
