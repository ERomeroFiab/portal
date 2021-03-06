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
use App\Models\GestionesHistoricas;
use Carbon\Carbon;

class AjaxController extends Controller
{
    public function get_tabla_empresas( Request $request )
    {
        $relations = [
            'razones_sociales',
            'representante',
            'gestiones',
        ];
        
        return DataTables::eloquent( Empresa::query()->withCount($relations) )
                            ->filter(function ($query) use ($request ) {
                                
                                if ( $request->get('search_by_empresas') !== null ) {
                                    $query->where('id', $request->get('search_by_empresas'));
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
                                if ($request->get("SEARCH_BY_GESTIONES_COUNT") !== null){
                                    $query->has("gestiones", $request->get('SEARCH_BY_GESTIONES_COUNT'));
                                }
                            })
                            
                            ->addColumn('representante', function ($dato) {
                                if ( $dato->representante ) {
                                    return $dato->representante->name;
                                }
                                return " ";
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

    public function get_tabla_empresas_as_consultor( Request $request )
    {
        $relations = [
            'razones_sociales',
            'representante',
            'gestiones',
        ];
        
        return DataTables::eloquent( Empresa::query()->withCount($relations) )
                            ->filter(function ($query) use ($request ) {
                                
                                if ( $request->get('search_by_empresas') !== null ) {
                                    $query->where('id', $request->get('search_by_empresas'));
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
                                if ($request->get("SEARCH_BY_GESTIONES_COUNT") !== null){
                                    $query->has("gestiones", $request->get('SEARCH_BY_GESTIONES_COUNT'));
                                }
                            })
                            
                            ->addColumn('representante', function ($dato) {
                                if ( $dato->representante ) {
                                    return $dato->representante->name;
                                }
                                return " ";
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
                                $data['path_to_show']    = route('consultor.empresas.show', ['id' => $dato->id]);

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
            'gestiones',
            'invoices',
            

        ];

        return DataTables::eloquent( RazonSocial::query()->withCount( $relations )

        )->filter(function ($query) use ($request) {
                            
            //filtros Tabla
            if ($request->get("SEARCH_BY_ID") !== null){
                $query->where("ID","like","%" . $request->get('SEARCH_BY_ID') . "%");
            }

            if ($request->get("SEARCH_BY_EMPRESA") !== null){
                $palabra = "%".$request->get('SEARCH_BY_EMPRESA')."%";
                $query->whereHas("empresa", function ($q) use ($palabra){
                    $q->where('nombre', 'like', $palabra);
                });
            }

            if ($request->get("SEARCH_BY_NOMBRE") !== null){
                $query->where("NOMBRE", "like", "%" . $request->get('SEARCH_BY_NOMBRE') . "%");
            }

            if ($request->get("SEARCH_BY_RUT") !== null){
                $query->where("RUT", "like", "%" . $request->get('SEARCH_BY_RUT') . "%");
            }

            if ($request->get("SEARCH_BY_CIUDAD") !== null){
                $query->where("CIUDAD", "like", "%" . $request->get('SEARCH_BY_CIUDAD') . "%");
            }

            if ($request->get("SEARCH_BY_CODIGO_POSTAL") !== null){
                $query->where("CODIGO_POSTAL", "like", "%" . $request->get('SEARCH_BY_CODIGO_POSTAL') . "%");
            }

            if ($request->get("SEARCH_BY_DIRECCION") !== null){
                $query->where("DIRECCION", "like", "%" . $request->get('SEARCH_BY_DIRECCION') . "%");
            }

            if ($request->get("SEARCH_BY_NUMERO_DE_CUENTA_BANCARIA") !== null){
                $query->where("NUMERO_DE_CUENTA_BANCARIA", "like", "%" . $request->get('SEARCH_BY_NUMERO_DE_CUENTA_BANCARIA') . "%");
            }

            if ($request->get("SEARCH_BY_BANCO") !== null){
                $query->where("BANCO", "like", "%" . $request->get('SEARCH_BY_BANCO') . "%");
            }

            if ($request->get("SEARCH_BY_TIPO_DE_CUENTA") !== null){
                $query->where("TIPO_DE_CUENTA", "like", "%" . $request->get('SEARCH_BY_TIPO_DE_CUENTA') . "%");
            }

            if ($request->get("SEARCH_BY_GESTIONES") !== null){
                $query->has("GESTIONES", "like", "%" . $request->get('SEARCH_BY_GESTIONES') . "%");
            }


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

    public function get_tabla_razones_sociales_as_consultor( Request $request )
    {
        $relations = [
            'gestiones',
        ];

        return DataTables::eloquent( 
            
            RazonSocial::query()->withCount( $relations )

        )->filter(function ($query) use ($request) {
                            
            //filtros Tabla
            if ($request->get("SEARCH_BY_ID") !== null){
                $query->where("ID","like","%" . $request->get('SEARCH_BY_ID') . "%");
            }

            if ($request->get("SEARCH_BY_EMPRESA") !== null){
                $palabra = "%".$request->get('SEARCH_BY_EMPRESA')."%";
                $query->whereHas("empresa", function ($q) use ($palabra){
                    $q->where('nombre', 'like', $palabra);
                });
            }

            if ($request->get("SEARCH_BY_NOMBRE") !== null){
                $query->where("NOMBRE", "like", "%" . $request->get('SEARCH_BY_NOMBRE') . "%");
            }

            if ($request->get("SEARCH_BY_RUT") !== null){
                $query->where("RUT", "like", "%" . $request->get('SEARCH_BY_RUT') . "%");
            }

            if ($request->get("SEARCH_BY_CIUDAD") !== null){
                $query->where("CIUDAD", "like", "%" . $request->get('SEARCH_BY_CIUDAD') . "%");
            }

            if ($request->get("SEARCH_BY_CODIGO_POSTAL") !== null){
                $query->where("CODIGO_POSTAL", "like", "%" . $request->get('SEARCH_BY_CODIGO_POSTAL') . "%");
            }

            if ($request->get("SEARCH_BY_DIRECCION") !== null){
                $query->where("DIRECCION", "like", "%" . $request->get('SEARCH_BY_DIRECCION') . "%");
            }

            if ($request->get("SEARCH_BY_NUMERO_DE_CUENTA_BANCARIA") !== null){
                $query->where("NUMERO_DE_CUENTA_BANCARIA", "like", "%" . $request->get('SEARCH_BY_NUMERO_DE_CUENTA_BANCARIA') . "%");
            }

            if ($request->get("SEARCH_BY_BANCO") !== null){
                $query->where("BANCO", "like", "%" . $request->get('SEARCH_BY_BANCO') . "%");
            }

            if ($request->get("SEARCH_BY_TIPO_DE_CUENTA") !== null){
                $query->where("TIPO_DE_CUENTA", "like", "%" . $request->get('SEARCH_BY_TIPO_DE_CUENTA') . "%");
            }

            if ($request->get("SEARCH_BY_GESTIONES") !== null){
                $query->has("GESTIONES", $request->get('SEARCH_BY_GESTIONES'));
            }


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
            $data['path_to_show']    = route('consultor.razones-sociales.show', ['id' => $dato->id]);

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
        $starts_gestion = $request->get('search_by_periodo_gestion_desde') ? Carbon::parse( $request->get('search_by_periodo_gestion_desde') ) : Carbon::now('America/Santiago')
        ->setTimeZone('America/Santiago')->subYears(100);
        $ends_gestion = $request->get('search_by_periodo_gestion_hasta') ? Carbon::parse( $request->get('search_by_periodo_gestion_hasta') ) : Carbon::now('America/Santiago')
        ->setTimeZone('America/Santiago')->addYears(100);

        $starts_depositado = $request->get('search_by_periodo_depositado_desde ') ? Carbon::parse( $request->get('search_by_periodo_depositado_desde ') ) : Carbon::now('America/Santiago')
        ->setTimeZone('America/Santiago')->subYears(100);
        $ends_depositado = $request->get('search_by_periodo_depositado_hasta') ? Carbon::parse( $request->get('search_by_periodo_depositado_hasta') ) : Carbon::now('America/Santiago')
        ->setTimeZone('America/Santiago')->addYears(100);
        
        return DataTables::eloquent( 
            Gestion::query()->wherehas('razon_social', function($q3) use ($empresa_id) {
                $q3->wherehas('empresa', function($q4) use ($empresa_id) {
                    $q4->where('id', $empresa_id);
                });
            })
            ->where('origin', "ST")

        )->filter(function ($query) use ($request, $starts_gestion,$ends_gestion,$starts_depositado,$ends_depositado) {
                            
            if ( $request->get('search_by_razon_social') !== null ) {
                $query->where('razon_social_id', $request->get('search_by_razon_social'));
            }
                            
            if ( $request->get('search_by_gestion') !== null ) {
                $query->where('gestion', $request->get('search_by_gestion'));
            }
                            
            if ( $request->get('search_by_motivo') !== null ) {
                $query->where('motivo', $request->get('search_by_motivo'));
            }
                            
            if ( $request->get('search_by_rut') !== null ) {
                $rut = "%".$request->get('search_by_rut')."%";
                $query->whereHas('razon_social', function($q) use ($rut) {
                    $q->where('rut', 'like', $rut);
                });
            }

            $query->whereBetween('periodo_gestion', [$starts_gestion, $ends_gestion]);

            // $query->whereBetween('fecha_deposito', [$starts_depositado, $ends_depositado]);

            if ($request->get("search_by_banco") !== null){
                $palabra = "%".$request->get('search_by_banco')."%";
                $query->whereHas('razon_social',function($q) use ($palabra){
                    $q->where('banco','like',$palabra);
                });
            }

            if ($request->get("search_by_monto_depositado") !== null){
                $query->where("monto_depositado","like","%" . $request->get('search_by_monto_depositado') . "%");
            }

            if ($request->get("search_by_honorarios_fiabilis") !== null){
                $query->where("honorarios_fiabilis","like","%" . $request->get('search_by_honorarios_fiabilis') . "%");
            }

            if ($request->get("search_by_montos_facturados") !== null){
                $query->where("montos_facturados","like","%" . $request->get('search_by_montos_facturados') . "%");
            }

            if ($request->get("search_by_monto_a_facturar") !== null){
                $query->where("monto_a_facturar","like","%" . $request->get('search_by_monto_a_facturar') . "%");
            }

            if ( $request->get('search_by_status') !== null ) {
                $query->where('status', $request->get('search_by_status'));
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
        ->editColumn('monto_depositado', function($dato){
            return $dato->monto_depositado ? "$ ".number_format( $dato->monto_depositado, 0, ",", ".") : null;
        })
        ->editColumn('honorarios_fiabilis', function($dato){
            return $dato->honorarios_fiabilis ? "$ ".number_format( $dato->honorarios_fiabilis, 0, ",", ".") : null;
        })
        ->editColumn('montos_facturados', function($dato){
            return $dato->montos_facturados ? "$ ".number_format( $dato->montos_facturados, 0, ",", ".") : null;
        })
        ->editColumn('monto_a_facturar', function($dato){
            return $dato->monto_a_facturar ? "$ ".number_format( $dato->monto_a_facturar, 0, ",", ".") : null;
        })
        ->toJson();
    }

    public function get_tabla_gestiones_by_empresa_as_consultor( Request $request )
    {

        $starts_gestion = $request->get('search_by_periodo_gestion_desde') ? Carbon::parse( $request->get('search_by_periodo_gestion_desde') ) : Carbon::now('America/Santiago')
        ->setTimeZone('America/Santiago')->subYears(100);
        $ends_gestion = $request->get('search_by_periodo_gestion_hasta') ? Carbon::parse( $request->get('search_by_periodo_gestion_hasta') ) : Carbon::now('America/Santiago')
        ->setTimeZone('America/Santiago')->addYears(100);
        
        return DataTables::eloquent( 

            Gestion::query()

        )->filter(function ($query) use ($request, $starts_gestion,$ends_gestion) {
                            
            if ( $request->get('search_by_razon_social') !== null ) {
                $palabra = "%".$request->get('search_by_razon_social')."%";
                $query->whereHas('razon_social', function($q) use ($palabra) {
                    $q->where('nombre', 'like', $palabra);
                });
            }

            if ( $request->get('search_by_rut') !== null ) {
                $rut = "%".$request->get('search_by_rut')."%";
                $query->whereHas('razon_social', function($q) use ($rut) {
                    $q->where('rut', 'like', $rut);
                });
            }
                            
            if ( $request->get('search_by_gestion') !== null ) {
                $query->where('gestion', $request->get('search_by_gestion'));
            }

            if ( $request->get('search_by_motivo') !== null ) {
                $query->where('motivo', $request->get('search_by_motivo'));
            }
                            


            // $query->whereBetween('periodo_gestion', [$starts_gestion, $ends_gestion]);

            if ($request->get("search_by_banco") !== null){
                $palabra = "%".$request->get('search_by_banco')."%";
                $query->whereHas('razon_social',function($q) use ($palabra){
                    $q->where('banco','like',$palabra);
                });
            }

            if ($request->get("search_by_monto_depositado") !== null){
                $query->where("monto_depositado","like","%" . $request->get('search_by_monto_depositado') . "%");
            }

            if ($request->get("search_by_honorarios_fiabilis") !== null){
                $query->where("honorarios_fiabilis","like","%" . $request->get('search_by_honorarios_fiabilis') . "%");
            }

            if ($request->get("search_by_montos_facturados") !== null){
                $query->where("montos_facturados","like","%" . $request->get('search_by_montos_facturados') . "%");
            }

            if ($request->get("search_by_monto_a_facturar") !== null){
                $query->where("monto_a_facturar","like","%" . $request->get('search_by_monto_a_facturar') . "%");
            }

            if ( $request->get('search_by_status') !== null ) {
                $query->where('status', $request->get('search_by_status'));
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
        ->editColumn('monto_depositado', function($dato){
            return $dato->monto_depositado ? "$ ".number_format( $dato->monto_depositado, 0, ",", ".") : null;
        })
        ->editColumn('honorarios_fiabilis', function($dato){
            return $dato->honorarios_fiabilis ? "$ ".number_format( $dato->honorarios_fiabilis, 0, ",", ".") : null;
        })
        ->editColumn('montos_facturados', function($dato){
            return $dato->montos_facturados ? "$ ".number_format( $dato->montos_facturados, 0, ",", ".") : null;
        })
        ->editColumn('monto_a_facturar', function($dato){
            return $dato->monto_a_facturar ? "$ ".number_format( $dato->monto_a_facturar, 0, ",", ".") : null;
        })
        ->toJson();
    }

    public function get_tabla_gestiones_historicas_by_empresa( Request $request )
    {
        $empresa_id = $request->get('search_by_empresa');
        $starts_gestion = $request->get('search_by_periodo_gestion_desde') ? Carbon::parse( $request->get('search_by_periodo_gestion_desde') ) : Carbon::now('America/Santiago')
        ->setTimeZone('America/Santiago')->subYears(100);
        $ends_gestion = $request->get('search_by_periodo_gestion_hasta') ? Carbon::parse( $request->get('search_by_periodo_gestion_hasta') ) : Carbon::now('America/Santiago')
        ->setTimeZone('America/Santiago')->addYears(100);

        $starts_depositado = $request->get('search_by_periodo_depositado_desde ') ? Carbon::parse( $request->get('search_by_periodo_depositado_desde ') ) : Carbon::now('America/Santiago')
        ->setTimeZone('America/Santiago')->subYears(100);
        $ends_depositado = $request->get('search_by_periodo_depositado_hasta') ? Carbon::parse( $request->get('search_by_periodo_depositado_hasta') ) : Carbon::now('America/Santiago')
        ->setTimeZone('America/Santiago')->addYears(100);
        
        return DataTables::eloquent( 
            Gestion::query()->whereHas('razon_social', function($q3) use ($empresa_id) {
                $q3->whereHas('empresa', function($q4) use ($empresa_id) {
                    $q4->where('id', $empresa_id);
                });
            })
            ->where('origin', "CN")

            )->filter(function ($query) use ($request, $starts_gestion, $ends_gestion, $starts_depositado, $ends_depositado) {
                            
                if ( $request->get('search_by_razon_social') !== null ) {
                    $query->where('razon_social_id', $request->get('search_by_razon_social'));
                }
                                
                if ( $request->get('search_by_gestion') !== null ) {
                    $query->where('gestion', $request->get('search_by_gestion'));
                }
                                
                if ( $request->get('search_by_motivo') !== null ) {
                    $query->where('motivo', $request->get('search_by_motivo'));
                }
                                
                if ( $request->get('search_by_rut') !== null ) {
                    $rut = "%".$request->get('search_by_rut')."%";
                    $query->whereHas('razon_social', function($q) use ($rut) {
                        $q->where('rut', 'like', $rut);
                    });
                }
    
                $query->whereBetween('periodo_gestion', [$starts_gestion, $ends_gestion]);
                
                // $query->whereBetween('fecha_deposito', [$starts_depositado, $ends_depositado]);
    
                if ($request->get("search_by_banco") !== null){
                    $palabra = "%".$request->get('search_by_banco')."%";
                    $query->whereHas('razon_social',function($q) use ($palabra){
                        $q->where('banco','like',$palabra);
                    });
                }
    
                if ($request->get("search_by_monto_depositado") !== null){
                    $query->where("monto_depositado","like","%" . $request->get('search_by_monto_depositado') . "%");
                }
    
                if ($request->get("search_by_honorarios_fiabilis") !== null){
                    $query->where("honorarios_fiabilis","like","%" . $request->get('search_by_honorarios_fiabilis') . "%");
                }
    
                if ($request->get("search_by_montos_facturados") !== null){
                    $query->where("montos_facturados","like","%" . $request->get('search_by_montos_facturados') . "%");
                }
    
    
                if ( $request->get('search_by_status') !== null ) {
                    $query->where('status', $request->get('search_by_status'));
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
        ->editColumn('monto_depositado', function($dato){
            return $dato->monto_depositado ? "$ ".number_format( $dato->monto_depositado, 0, ",", ".") : null;
        })
        ->editColumn('honorarios_fiabilis', function($dato){
            return $dato->honorarios_fiabilis ? "$ ".number_format( $dato->honorarios_fiabilis, 0, ",", ".") : null;
        })
        ->editColumn('montos_facturados', function($dato){
            return $dato->montos_facturados ? "$ ".number_format( $dato->montos_facturados, 0, ",", ".") : null;
        })
        ->editColumn('monto_a_facturar', function($dato){
            return $dato->monto_a_facturar ? "$ ".number_format( $dato->monto_a_facturar, 0, ",", ".") : null;
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
            ->where('origin', "ST")

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
        ->editColumn('monto_depositado', function($dato){
            return $dato->monto_depositado ? "$ ".number_format( $dato->monto_depositado, 0, ",", ".") : null;
        })
        ->editColumn('honorarios_fiabilis', function($dato){
            return $dato->honorarios_fiabilis ? "$ ".number_format( $dato->honorarios_fiabilis, 0, ",", ".") : null;
        })
        ->editColumn('montos_facturados', function($dato){
            return $dato->montos_facturados ? "$ ".number_format( $dato->montos_facturados, 0, ",", ".") : null;
        })
        ->editColumn('monto_a_facturar', function($dato){
            return $dato->monto_a_facturar ? "$ ".number_format( $dato->monto_a_facturar, 0, ",", ".") : null;
        })
        ->toJson();
    }

    public function get_tabla_gestiones_by_razon_social_as_consultor( Request $request )
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
        ->editColumn('monto_depositado', function($dato){
            return $dato->monto_depositado ? "$ ".number_format( $dato->monto_depositado, 0, ",", ".") : null;
        })
        ->editColumn('honorarios_fiabilis', function($dato){
            return $dato->honorarios_fiabilis ? "$ ".number_format( $dato->honorarios_fiabilis, 0, ",", ".") : null;
        })
        ->editColumn('montos_facturados', function($dato){
            return $dato->montos_facturados ? "$ ".number_format( $dato->montos_facturados, 0, ",", ".") : null;
        })
        ->editColumn('monto_a_facturar', function($dato){
            return $dato->monto_a_facturar ? "$ ".number_format( $dato->monto_a_facturar, 0, ",", ".") : null;
        })
        ->toJson();
    }

    public function get_tabla_servicios_por_cobrar_by_empresa( Request $request )
    {
        $empresa_id = $request->get('search_by_empresa');
        $starts_gestion = $request->get('search_by_periodo_gestion_desde') ? Carbon::parse( $request->get('search_by_periodo_gestion_desde') ) : Carbon::now('America/Santiago')
        ->setTimeZone('America/Santiago')->subYears(100);
        $ends_gestion = $request->get('search_by_periodo_gestion_hasta') ? Carbon::parse( $request->get('search_by_periodo_gestion_hasta') ) : Carbon::now('America/Santiago')
        ->setTimeZone('America/Santiago')->addYears(100);

        $starts_depositado = $request->get('search_by_periodo_depositado_desde ') ? Carbon::parse( $request->get('search_by_periodo_depositado_desde ') ) : Carbon::now('America/Santiago')
        ->setTimeZone('America/Santiago')->subYears(100);
        $ends_depositado = $request->get('search_by_periodo_depositado_hasta') ? Carbon::parse( $request->get('search_by_periodo_depositado_hasta') ) : Carbon::now('America/Santiago')
        ->setTimeZone('America/Santiago')->addYears(100);
        
        return DataTables::eloquent( 
            Gestion::query()->wherehas('razon_social', function($q3) use ($empresa_id) {
                $q3->wherehas('empresa', function($q4) use ($empresa_id) {
                    $q4->where('id', $empresa_id);
                });
            })
            ->whereNotNull('monto_a_facturar')
            ->whereNull('montos_facturados')
            ->where('origin', "ST")

            )->filter(function ($query) use ($request, $starts_gestion,$ends_gestion,$starts_depositado,$ends_depositado) {
                            
                if ( $request->get('search_by_razon_social') !== null ) {
                    $query->where('razon_social_id', $request->get('search_by_razon_social'));
                }
                                
                if ( $request->get('search_by_gestion') !== null ) {
                    $query->where('gestion', $request->get('search_by_gestion'));
                }
                                
                if ( $request->get('search_by_motivo') !== null ) {
                    $query->where('motivo', $request->get('search_by_motivo'));
                }
                                
                if ( $request->get('search_by_rut') !== null ) {
                    $rut = "%".$request->get('search_by_rut')."%";
                    $query->whereHas('razon_social', function($q) use ($rut) {
                        $q->where('rut', 'like', $rut);
                    });
                }
    
                $query->whereBetween('periodo_gestion', [$starts_gestion, $ends_gestion]);
    
                // $query->whereBetween('fecha_deposito', [$starts_depositado, $ends_depositado]);
    
                if ($request->get("search_by_banco") !== null){
                    $palabra = "%".$request->get('search_by_banco')."%";
                    $query->whereHas('razon_social',function($q) use ($palabra){
                        $q->where('banco','like',$palabra);
                    });
                }
    
                if ($request->get("search_by_monto_depositado") !== null){
                    $query->where("monto_depositado","like","%" . $request->get('search_by_monto_depositado') . "%");
                }
    
                if ($request->get("search_by_honorarios_fiabilis") !== null){
                    $query->where("honorarios_fiabilis","like","%" . $request->get('search_by_honorarios_fiabilis') . "%");
                }
    
                if ($request->get("search_by_montos_facturados") !== null){
                    $query->where("montos_facturados","like","%" . $request->get('search_by_montos_facturados') . "%");
                }
    
                if ($request->get("search_by_monto_a_facturar") !== null){
                    $query->where("monto_a_facturar","like","%" . $request->get('search_by_monto_a_facturar') . "%");
                }
    
                if ( $request->get('search_by_status') !== null ) {
                    $query->where('status', $request->get('search_by_status'));
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
        ->editColumn('monto_depositado', function($dato){
            return $dato->monto_depositado ? "$ ".number_format( $dato->monto_depositado, 0, ",", ".") : null;
        })
        ->editColumn('honorarios_fiabilis', function($dato){
            return $dato->honorarios_fiabilis ? "$ ".number_format( $dato->honorarios_fiabilis, 0, ",", ".") : null;
        })
        ->editColumn('montos_facturados', function($dato){
            return $dato->montos_facturados ? "$ ".number_format( $dato->montos_facturados, 0, ",", ".") : null;
        })
        ->editColumn('monto_a_facturar', function($dato){
            return $dato->monto_a_facturar ? "$ ".number_format( $dato->monto_a_facturar, 0, ",", ".") : null;
        })
        ->addIndexColumn()
        ->toJson();
    }

    public function get_tabla_servicios_por_cobrar_as_consultor( Request $request )
    {
       
        return DataTables::eloquent( 

            Gestion::query()
                ->whereNotNull('monto_a_facturar')
                ->whereNull('montos_facturados')

            )->filter(function ($query) use ($request) {
                            
                if ( $request->get('search_by_razon_social') !== null ) {
                    $query->where('razon_social_id', $request->get('search_by_razon_social'));
                }
                                
                if ( $request->get('search_by_gestion') !== null ) {
                    $query->where('gestion', $request->get('search_by_gestion'));
                }
                                
                if ( $request->get('search_by_motivo') !== null ) {
                    $query->where('motivo', $request->get('search_by_motivo'));
                }
                                
                if ( $request->get('search_by_rut') !== null ) {
                    $rut = "%".$request->get('search_by_rut')."%";
                    $query->whereHas('razon_social', function($q) use ($rut) {
                        $q->where('rut', 'like', $rut);
                    });
                }
    
    
                if ($request->get("search_by_banco") !== null){
                    $palabra = "%".$request->get('search_by_banco')."%";
                    $query->whereHas('razon_social',function($q) use ($palabra){
                        $q->where('banco','like',$palabra);
                    });
                }
    
                if ($request->get("search_by_monto_depositado") !== null){
                    $query->where("monto_depositado","like","%" . $request->get('search_by_monto_depositado') . "%");
                }
    
                if ($request->get("search_by_honorarios_fiabilis") !== null){
                    $query->where("honorarios_fiabilis","like","%" . $request->get('search_by_honorarios_fiabilis') . "%");
                }
    
                if ($request->get("search_by_montos_facturados") !== null){
                    $query->where("montos_facturados","like","%" . $request->get('search_by_montos_facturados') . "%");
                }
    
                if ($request->get("search_by_monto_a_facturar") !== null){
                    $query->where("monto_a_facturar","like","%" . $request->get('search_by_monto_a_facturar') . "%");
                }
    
                if ( $request->get('search_by_status') !== null ) {
                    $query->where('status', $request->get('search_by_status'));
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
        ->editColumn('monto_depositado', function($dato){
            return $dato->monto_depositado ? "$ ".number_format( $dato->monto_depositado, 0, ",", ".") : null;
        })
        ->editColumn('honorarios_fiabilis', function($dato){
            return $dato->honorarios_fiabilis ? "$ ".number_format( $dato->honorarios_fiabilis, 0, ",", ".") : null;
        })
        ->editColumn('montos_facturados', function($dato){
            return $dato->montos_facturados ? "$ ".number_format( $dato->montos_facturados, 0, ",", ".") : null;
        })
        ->editColumn('monto_a_facturar', function($dato){
            return $dato->monto_a_facturar ? "$ ".number_format( $dato->monto_a_facturar, 0, ",", ".") : null;
        })
        ->addIndexColumn()
        ->toJson();
    }
}
