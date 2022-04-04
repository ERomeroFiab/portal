<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use App\Imports\GestionesHistoricasImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Gestion;

class HerramientaController extends Controller
{
    public function admin_index()
    {
        return view('administrador.herramientas.index');
    }

    public function import_excel_historico( Request $request )
    {
        Validator::make($request->all(), [
            'file'       => 'required|mimes:xlsx',
        ])->validate();

        $this->remove_old_gestiones_from_excel();

        $new_import = new GestionesHistoricasImport();
        Excel::import($new_import, $request->file('file'));

        return redirect()->route('admin.herramientas.index')->with('success', "Se cargó el excel correctamente.");
    }

    public function remove_old_gestiones_from_excel()
    {
        $gestiones = Gestion::where('origin', 'CN')->get();
        foreach ($gestiones as $gestion) {
            if ( $gestion ) {
                $gestion->delete();
            }
        }
        return;
    }
}
