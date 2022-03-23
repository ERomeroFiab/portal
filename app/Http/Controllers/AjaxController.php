<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Empresa;
use Yajra\DataTables\Facades\DataTables;
use App\Models\User;
use App\Models\MissionMotiveEco;

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
                                
                                if ( $request->get('search_by_empresa') !== null ) {
                                    $query->where('id', $request->get('search_by_empresa'));
                                }

                            })
                            ->addColumn('email', function ($dato) {
                                if ( $dato->representante ) {
                                    return $dato->representante->email;
                                }
                                return "-";
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

    public function get_tabla_usuarios( Request $request )
    {
        return DataTables::eloquent( User::query() )
                            ->filter(function ($query) use ($request) {
                                
                                // if ( $request->get('SEARCH_BY_VILLE') !== null ) {
                                //     $query->where('VILLE', $request->get('SEARCH_BY_VILLE'));
                                // }

                            })
                            ->addColumn('action', function ($dato) {
                                $data['id'] = $dato->id;
                                $data['path_to_show']    = route('admin.usuarios.show', ['id' => $dato->id]);
                                if ( $dato->id !== 1 ) {
                                    $data['path_to_edit']    = route('admin.usuarios.edit', ['id' => $dato->id]);
                                    $data['path_to_destroy'] = route('admin.usuarios.destroy', ['id' => $dato->id]);
                                }

                                return $data;
                            })
                            ->addColumn('empresa', function ($dato) {
                                if ( $dato->empresa ) {
                                    return $dato->empresa->nombre;
                                }
                                return "-";
                            })
                            ->addColumn('razones_sociales_count', function ($dato) {
                                if ( $dato->empresa ) {
                                    return $dato->empresa->razones_sociales->count();
                                }
                                return "-";
                            })
                            ->toJson();
    }

    public function get_tabla_mission_motive_eco_by_empresa( Request $request )
    {
        $empresa_id = $request->get('search_by_empresa');
        
        return DataTables::eloquent( 
            MissionMotiveEco::query()->wherehas('mission_motive', function($q1) use ($empresa_id) {
                $q1->wherehas('mission', function($q2) use ($empresa_id) {
                    $q2->wherehas('razon_social', function($q3) use ($empresa_id) {
                        $q3->wherehas('empresa', function($q4) use ($empresa_id) {
                            $q4->where('id', $empresa_id);
                        });
                    });
                });
            })

            )->filter(function ($query) use ($request) {
                                
                // if ( $request->get('search_by_empresa') !== null ) {
                //     $empresa_id = $request->get('search_by_empresa');
                //     $query
                // }

            })
            ->addColumn('razon_social', function ($dato) {
                return $dato->mission_motive->mission->razon_social->nombre;
            })
            ->addColumn('rut', function ($dato) {
                return $dato->mission_motive->mission->razon_social->rut;
            })
            ->addColumn('motivo', function ($dato) {
                return $dato->mission_motive->MOTIF;
            })
            ->addColumn('gestion', function ($dato) {
                // return $dato->mission->CURRENT_STEP;
                return "-";
            })
            ->addColumn('fecha_de_gestion', function ($dato) {
                return $dato->mission_motive->SOUS_MOTIF_1;
            })
            ->addColumn('banco', function ($dato) {
                return $dato->mission_motive->mission->razon_social->banco;
            })
            ->addColumn('invoice', function ($dato) {
                return "-";
            })
            // ->addColumn('action', function ($dato) {
            //     //
            // })
            ->toJson();
    }
}
