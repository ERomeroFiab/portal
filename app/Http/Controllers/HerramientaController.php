<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HerramientaController extends Controller
{
    public function admin_index()
    {
        return view('administrador.herramientas.index');
    }
}
