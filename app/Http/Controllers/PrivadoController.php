<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PrivadoController extends Controller
{
    public function cambioPrecios() {
        include(app_path() . '/privado/cambio-precios.php');
    }
    
    public function desbloquearPedidos(Request $request) {
        /* include(app_path() . '/privado/desbloquear-pedidos.php'); */
        return view('gfc.privado.desbloquear-pedidos', compact('request'));
    }
    
    public function descargarExcels(Request $request) {
        return view('gfc.privado.descargar-excels', compact('request'));
        /* include(app_path() . '/privado/descargar-excels.php'); */
    }
    
    public function uploadDtocompra() {
        include(app_path() . '/privado/upload_dtocompra.php.php');
    }
    
    public function consultaStockNetosEditor() {
        include(app_path() . '/privado/consulta_stock-netos_editor.php');
    }
}
