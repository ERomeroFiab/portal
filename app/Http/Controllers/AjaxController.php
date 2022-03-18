<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Empresa;
use Yajra\DataTables\Facades\DataTables;

class AjaxController extends Controller
{
    public function get_tabla_empresas( Request $request )
    {
        $relations = [
            'razones_sociales',
            'representante',
        ];
        
        $datos = Empresa::with( $relations )->withCount($relations);
        
        return DataTables::eloquent( $datos )
                            ->filter(function ($query) use ($request) {
                                
                                // if ( $request->get('SEARCH_BY_VILLE') !== null ) {
                                //     $query->where('VILLE', $request->get('SEARCH_BY_VILLE'));
                                // }

                            })
                            ->addColumn('email', function ($dato) {
                                if ( $dato->representante ) {
                                    return $dato->representante->email;
                                }
                                return "-";
                            })
                            ->addColumn('razones_sociales_count', function ($dato) {
                                return $dato->razones_sociales->count();
                            })
                            ->editColumn('cliente', function ($dato) {
                                if ( $dato->representante ) {
                                    return $dato->representante->name;
                                }
                                return "-";
                            })
                            ->editColumn('nombre', function ($dato) {
                                $data['nombre'] = $dato->nombre;
                                $data['id']     = $dato->id;
                                return $data;
                            })
                            ->addColumn('action', function ($dato) {
                                return '<a href="'.route('admin.empresas.show', ['id' => $dato->id]).'" class="btn btn-sm btn-info">Ver</a>';
                            })
                            ->toJson();
    }
}
