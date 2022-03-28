<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Empresa;
use Yajra\DataTables\Facades\DataTables;
use App\Models\User;
use App\Models\MissionMotiveEco;
use App\Models\Mission;
use App\Models\MissionMotive;
use App\Models\Invoice;
use App\Models\RazonSocial;
use App\Models\InvoiceLigne;
use App\Models\Gestion;

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

                                //filtros Tabla
                                if ($request->get("SEARCH_BY_NOMBRE") !== null){
                                    $query->where("nombre","like","%" . $request->get('SEARCH_BY_NOMBRE') . "%");
                                }
                                if ($request->get("SEARCH_BY_REPRESENTANTE") !== null){
                                    $palabra = "%".$request->get("SEARCH_BY_REPRESENTANTE")."%";
                                    $query->whereHas("representante", function($q) use ($palabra){
                                        $q->where('name', 'like', $palabra);
                                    });
                                }
                                if ($request->get("SEARCH_BY_RAZONES_SOCIALES_COUNT") !== null){
                                    $query->has("razones_sociales", $request->get('SEARCH_BY_RAZONES_SOCIALES_COUNT'));
                                }
                            })
                            
                            ->addColumn('representante', function ($dato) {
                                if ( $dato->representante ) {
                                    return $dato->representante->name;
                                }
                                return "-";
                            })
                            ->orderColumn('representante', function ($query, $order) {
                                $query->orderBy(
                                    User::select('name')->whereColumn('empresas.id', 'empresa_id'),
                                    $order
                                );
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
                                
                                //filtros Tabla
                                if ($request->get("SEARCH_BY_NAME") !== null){
                                    $query->where("name","like","%" . $request->get('SEARCH_BY_NAME') . "%");
                                }
                                if ($request->get("SEARCH_BY_EMAIL") !== null){
                                    $query->where("email","like","%" . $request->get('SEARCH_BY_EMAIL') . "%");
                                }
                                if ($request->get("SEARCH_BY_RUT") !== null){
                                    $query->where("rut","like","%" . $request->get('SEARCH_BY_RUT') . "%");
                                }
                                if ($request->get("SEARCH_BY_ROL") !== null){
                                    $query->where("rol","like","%" . $request->get('SEARCH_BY_ROL') . "%");
                                }
                                if ($request->get("SEARCH_BY_EMPRESA") !== null){
                                    $palabra = "%".$request->get('SEARCH_BY_EMPRESA')."%";
                                    $query->whereHas("empresa", function ($q) use ($palabra){
                                        $q->where('nombre', 'like', $palabra);
                                    });
                                }
                                if ($request->get("SEARCH_BY_RAZONES_SOCIALES_COUNT") !== null){
                                    $cantidad_de_razones_sociales = $request->get('SEARCH_BY_RAZONES_SOCIALES_COUNT');
                                    $query->whereHas("empresa", function ($q) use ($cantidad_de_razones_sociales){
                                        $q->has('razones_sociales', $cantidad_de_razones_sociales);
                                    });
                                }

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
                            ->orderColumn('empresa', function ($query, $order){
                                $query->orderBy(
                                    Empresa::select('nombre')->whereColumn('empresas.id', 'empresa_id'),
                                    $order
                                );
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
            MissionMotiveEco::query()->wherehas('razon_social', function($q3) use ($empresa_id) {
                $q3->wherehas('empresa', function($q4) use ($empresa_id) {
                    $q4->where('id', $empresa_id);
                });
            })

        )->filter(function ($query) use ($request) {
                            
            if ( $request->get('search_by_razon_social') !== null ) {
                $query->where('razon_social_id', $request->get('search_by_razon_social'));
            }
                            
            if ( $request->get('search_by_rut') !== null ) {
                $rut = "%".$request->get('search_by_rut')."%";
                $query->whereHas('razon_social', function($q) use ($rut) {
                    $q->where('rut', 'like', $rut);
                });
            }

        })
        ->addColumn('razon_social', function ($dato) {
            return $dato->razon_social->nombre;
        })
        ->addColumn('rut', function ($dato) {
            return $dato->razon_social->rut;
        })
        ->addColumn('motivo', function ($dato) {
            return $dato->mission_motive->mission->PRODUIT ?? "-";
        })
        ->addColumn('banco', function ($dato) {
            return $dato->razon_social->banco ?? "-";
        })
        ->addColumn('honorarios_fiabilis', function ($dato) {
            if ( $dato->invoice_ligne ) {
                return $dato->invoice_ligne->AMOUNT ?? "-";
            }
            return "-";
        })
        ->addColumn('montos_facturados', function ($dato) {
            if ( $dato->invoice_ligne ) {
                return $dato->invoice_ligne->AMOUNT ?? "-";
            }
            return "-";
        })
        ->addColumn('monto_a_facturar', function ($dato) {
            if ( !$dato->invoice_ligne ) {
                return ($dato->ECO_PRESENTEE * 0.3);
            }
            return "-";
        })
        ->orderColumn('razon_social', function($query, $order){
            $query->orderBy(
                RazonSocial::select('nombre')->whereColumn('razon_socials.id', 'razon_social_id'),
                $order
            );
        })
        ->orderColumn('rut', function($query, $order){
            $query->orderBy(
                RazonSocial::select('rut')->whereColumn('razon_socials.id', 'razon_social_id'),
                $order
            );
        })
        ->orderColumn('motivo', function($query, $order){
            $query->orderBy(
                Mission::select('PRODUIT')->whereColumn('missions.id', 'mission_id'),
                $order
            );
        })
        ->orderColumn('honorarios_fiabilis', function($query, $order){
            $query->orderBy(
                InvoiceLigne::select('AMOUNT')->whereColumn('mission_motive_ecos.id', 'mission_motive_eco_id'),
                $order
            );
        })
        ->toJson();
    }

    public function get_tabla_missions( Request $request )
    {
        $razon_social_id = $request->get('search_by_razon_social_id');
        
        return DataTables::eloquent( 

            Mission::query()->wherehas('razon_social', function($q1) use ($razon_social_id) {
                $q1->where('id', $razon_social_id);
            })

        )->filter(function ($query) use ($request) {
                            
            // if ( $request->get('search_by_xxxxx') !== null ) {
            //     $query->where('xxxxx', $request->get('search_by_xxxxx'));
            // }

        })
        // ->addColumn('razon_social', function ($dato) {
        //     return $dato->razon_social->nombre;
        // })
        ->toJson();
    }

    public function get_tabla_motives( Request $request )
    {
        $razon_social_id = $request->get('search_by_razon_social_id');
        
        return DataTables::eloquent( 

            MissionMotive::query()->wherehas('mission', function($q1) use ($razon_social_id) {
                $q1->wherehas('razon_social', function($q2) use ($razon_social_id) {
                    $q2->where('id', $razon_social_id);
                });
            })

        )->filter(function ($query) use ($request) {
                            
            // if ( $request->get('search_by_xxxxx') !== null ) {
            //     $query->where('xxxxx', $request->get('search_by_xxxxx'));
            // }

        })
        // ->addColumn('razon_social', function ($dato) {
        //     return $dato->razon_social->nombre;
        // })
        ->toJson();
    }

    public function get_tabla_ecos( Request $request )
    {
        $razon_social_id = $request->get('search_by_razon_social_id');
        
        return DataTables::eloquent( 

            MissionMotiveEco::query()->wherehas('razon_social', function($q1) use ($razon_social_id) {
                $q1->where('id', $razon_social_id);
            })

        )->filter(function ($query) use ($request) {
                            
            // if ( $request->get('search_by_xxxxx') !== null ) {
            //     $query->where('xxxxx', $request->get('search_by_xxxxx'));
            // }

        })
        // ->addColumn('razon_social', function ($dato) {
        //     return $dato->razon_social->nombre;
        // })
        ->toJson();
    }

    public function get_tabla_invoices( Request $request )
    {
        $razon_social_id = $request->get('search_by_razon_social_id');
        
        return DataTables::eloquent( 

            Invoice::query()->wherehas('razon_social', function($q1) use ($razon_social_id) {
                $q1->where('id', $razon_social_id);
            })

        )->filter(function ($query) use ($request) {
                            
            // if ( $request->get('search_by_xxxxx') !== null ) {
            //     $query->where('xxxxx', $request->get('search_by_xxxxx'));
            // }

        })
        // ->addColumn('razon_social', function ($dato) {
        //     return $dato->razon_social->nombre;
        // })
        ->toJson();
    }

    public function get_tabla_razones_sociales( Request $request )
    {
        $relations = [
            'missions',
            'invoices',
        ];

        return DataTables::eloquent( 

            RazonSocial::query()->withCount( $relations )

        )->filter(function ($query) use ($request) {
                            
            // if ( $request->get('search_by_xxxxx') !== null ) {
            //     $query->where('xxxxx', $request->get('search_by_xxxxx'));
            // }

        })
        ->addColumn('empresa', function ($dato) {
            return $dato->empresa->nombre;
        })
        ->orderColumn('empresa', function ($query, $order) {
            $query->orderBy( 
                Empresa::select('nombre')->whereColumn('empresas.id', 'empresa_id'), 
                $order 
            );
        })
        ->addColumn('action', function ($dato) {
            $data['id'] = $dato->id;
            $data['path_to_show']    = route('admin.razones-sociales.show', ['id' => $dato->id]);
            $data['path_to_edit']    = route('admin.razones-sociales.edit', ['id' => $dato->id]);
            $data['path_to_destroy'] = route('admin.razones-sociales.destroy', ['id' => $dato->id]);

            return $data;
        })
        ->toJson();
    }

    public function get_tabla_lignes( Request $request )
    {
        $razon_social_id = $request->get('search_by_razon_social_id');
        
        return DataTables::eloquent( 

            InvoiceLigne::query()->wherehas('razon_social', function($q1) use ($razon_social_id) {
                $q1->where('id', $razon_social_id);
            })

        )->filter(function ($query) use ($request) {
                            
            // if ( $request->get('search_by_xxxxx') !== null ) {
            //     $query->where('xxxxx', $request->get('search_by_xxxxx'));
            // }

        })
        // ->addColumn('razon_social', function ($dato) {
        //     return $dato->razon_social->nombre;
        // })
        ->toJson();
    }

    public function get_tabla_gestiones_by_empresa( Request $request )
    {
        $empresa_id = $request->get('search_by_empresa');
        
        return DataTables::eloquent( 
            Gestion::query()->wherehas('razon_social', function($q3) use ($empresa_id) {
                $q3->wherehas('empresa', function($q4) use ($empresa_id) {
                    $q4->where('id', $empresa_id);
                });
            })

        )->filter(function ($query) use ($request) {
                            
            if ( $request->get('search_by_razon_social') !== null ) {
                $query->where('razon_social_id', $request->get('search_by_razon_social'));
            }
                            
            if ( $request->get('search_by_rut') !== null ) {
                $rut = "%".$request->get('search_by_rut')."%";
                $query->whereHas('razon_social', function($q) use ($rut) {
                    $q->where('rut', 'like', $rut);
                });
            }

        })
        ->addColumn('razon_social', function ($dato) {
            return $dato->razon_social->nombre;
        })
        ->addColumn('rut', function ($dato) {
            return $dato->razon_social->rut;
        })
        ->addColumn('banco', function ($dato) {
            return $dato->razon_social->banco ?? "-";
        })
        ->orderColumn('razon_social', function($query, $order){
            $query->orderBy(
                RazonSocial::select('nombre')->whereColumn('razon_socials.id', 'razon_social_id'),
                $order
            );
        })
        ->orderColumn('rut', function($query, $order){
            $query->orderBy(
                RazonSocial::select('rut')->whereColumn('razon_socials.id', 'razon_social_id'),
                $order
            );
        })
        ->orderColumn('banco', function($query, $order){
            $query->orderBy(
                RazonSocial::select('banco')->whereColumn('razon_socials.id', 'razon_social_id'),
                $order
            );
        })
        ->toJson();
    }

    public function get_tabla_gestiones_by_razon_social( Request $request )
    {
        $razon_social_id = $request->get('search_by_razon_social_id');
        
        return DataTables::eloquent( 
            Gestion::query()->wherehas('razon_social', function($q) use ($razon_social_id) {
                $q->where('id', $razon_social_id);
            })

        )->filter(function ($query) use ($request) {
                            
            if ( $request->get('search_by_rut') !== null ) {
                $rut = "%".$request->get('search_by_rut')."%";
                $query->whereHas('razon_social', function($q) use ($rut) {
                    $q->where('rut', 'like', $rut);
                });
            }

        })
        ->orderColumn('razon_social', function($query, $order){
            $query->orderBy(
                RazonSocial::select('nombre')->whereColumn('razon_socials.id', 'razon_social_id'),
                $order
            );
        })
        ->orderColumn('rut', function($query, $order){
            $query->orderBy(
                RazonSocial::select('rut')->whereColumn('razon_socials.id', 'razon_social_id'),
                $order
            );
        })
        ->orderColumn('banco', function($query, $order){
            $query->orderBy(
                RazonSocial::select('banco')->whereColumn('razon_socials.id', 'razon_social_id'),
                $order
            );
        })
        ->toJson();
    }

    public function get_tabla_servicios_por_cobrar_by_empresa( Request $request )
    {
        $empresa_id = $request->get('search_by_empresa');
        
        return DataTables::eloquent( 
            Gestion::query()->wherehas('razon_social', function($q3) use ($empresa_id) {
                $q3->wherehas('empresa', function($q4) use ($empresa_id) {
                    $q4->where('id', $empresa_id);
                });
            })
            ->whereNotNull('monto_a_facturar')

        )->filter(function ($query) use ($request) {
                            
            if ( $request->get('search_by_razon_social') !== null ) {
                $query->where('razon_social_id', $request->get('search_by_razon_social'));
            }
                            
            if ( $request->get('search_by_rut') !== null ) {
                $rut = "%".$request->get('search_by_rut')."%";
                $query->whereHas('razon_social', function($q) use ($rut) {
                    $q->where('rut', 'like', $rut);
                });
            }

        })
        ->addColumn('razon_social', function ($dato) {
            return $dato->razon_social->nombre;
        })
        ->addColumn('rut', function ($dato) {
            return $dato->razon_social->rut;
        })
        ->addColumn('banco', function ($dato) {
            return $dato->razon_social->banco ?? "-";
        })
        ->orderColumn('razon_social', function($query, $order){
            $query->orderBy(
                RazonSocial::select('nombre')->whereColumn('razon_socials.id', 'razon_social_id'),
                $order
            );
        })
        ->orderColumn('rut', function($query, $order){
            $query->orderBy(
                RazonSocial::select('rut')->whereColumn('razon_socials.id', 'razon_social_id'),
                $order
            );
        })
        ->orderColumn('banco', function($query, $order){
            $query->orderBy(
                RazonSocial::select('banco')->whereColumn('razon_socials.id', 'razon_social_id'),
                $order
            );
        })
        ->toJson();
    }
}
