<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Empresa;
use Yajra\DataTables\Facades\DataTables;
use App\Models\User;

class AjaxController extends Controller
{
    public function get_tabla_empresas( Request $request )
    {
        $relations = [
            'razones_sociales',
            'representante',
        ];
        
        return DataTables::eloquent( Empresa::query()->withCount($relations) )
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
                                $data['id'] = $dato->id;
                                $data['path_to_show']    = route('admin.empresas.show', ['id' => $dato->id]);
                                $data['path_to_edit']    = route('admin.empresas.edit', ['id' => $dato->id]);
                                $data['path_to_destroy'] = route('admin.empresas.destroy', ['id' => $dato->id]);

                                return $data;
                            })
                            ->toJson();
    }

<<<<<<< HEAD
    public function get_tabla_users( Request $request )
    {
        $relations = [
        ];
        
        $datos = Empresa::with( $relations );
=======
    public function get_tabla_usuarios( Request $request )
    {
        
        $datos = User::query();
>>>>>>> 8a8e4e07224d0615d8bde093bef8d35435d16d16
        
        return DataTables::eloquent( $datos )
                            ->filter(function ($query) use ($request) {
                                
                                // if ( $request->get('SEARCH_BY_VILLE') !== null ) {
                                //     $query->where('VILLE', $request->get('SEARCH_BY_VILLE'));
                                // }

                            })
<<<<<<< HEAD
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
                                $data['id'] = $dato->id;
                                $data['path_to_show']    = route('admin.usesr.show', ['id' => $dato->id]);
                                $data['path_to_edit']    = route('admin.users.edit', ['id' => $dato->id]);
                                $data['path_to_destroy'] = route('admin.users.destroy', ['id' => $dato->id]);

                                return $data;
                            })
=======
                            // ->addColumn('action', function ($dato) {
                            //     $data['id'] = $dato->id;
                            //     $data['path_to_show']    = route('admin.empresas.show', ['id' => $dato->id]);
                            //     $data['path_to_edit']    = route('admin.empresas.edit', ['id' => $dato->id]);
                            //     $data['path_to_destroy'] = route('admin.empresas.destroy', ['id' => $dato->id]);

                            //     return $data;
                            // })
>>>>>>> 8a8e4e07224d0615d8bde093bef8d35435d16d16
                            ->toJson();
    }
}
