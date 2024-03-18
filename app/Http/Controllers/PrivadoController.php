<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PrivadoController extends Controller
{
    public function cambioPrecios() {
        include(app_path() . '\privado\cambio-precios.php');
    }
}
