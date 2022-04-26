<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use App\Models\Empresa;
use App\Models\RazonSocial;
use App\Models\User;
use App\Models\Mission;
use App\Models\MissionMotive;
use App\Models\MissionMotiveEco;
use App\Models\Invoice;
use App\Models\InvoiceLigne;
use App\Models\Gestion;
use Carbon\Carbon;

class SilverToolController extends Controller
{
    public function update_database_first_time()
    {   
        set_time_limit(12000);
        $empresas = $this->get_datos_de_api( config('services.PATH_TO_SILVERTOOL_DATABASE_MANAGER')."empresas/get_empresas_name" );

        if ( !$empresas ) {
            return back()->with('error', "Hubo un error al momento de consultar la api: ".config('services.PATH_TO_SILVERTOOL_DATABASE_MANAGER')."empresas/get_empresas_name");
        }

        foreach ($empresas as $empresa) {
            $new_empresa = $this->create_new_empresa( $empresa['name'] );
            $this->register_razones_sociales( $new_empresa, $empresa['razones_sociales'] );
        }
        
        return back()->with('success', "Base de datos actualizada correctamente.");
            
    }

    public function get_datos_de_api( $url )
    {
        try {
            
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Access-Control-Allow-Origin' => '*',
                'Access-Control-Allow-Methods' => 'GET, POST, PUT, DELETE, OPTIONS',
                'Access-Control-Allow-Headers' => 'Origin, Content-Type, Accept, Authorization, X-Request-With',
                'Access-Control-Allow-Credentials' => 'true',
                'CUSTOM' => config('services.TOKEN_FOR_REQUESTS_TO_SILVER'),
            ])->get($url);
            
            if ( !$response->ok() ) {
                return null;
            } 
            
            return $response->json();
            
        } catch (\Throwable $th) {
            info('HUBO UN ERROR AL MOMENTO DE CONSULTAR LA API '.$url);
            return null;
        }

    }

    public function send_data_to_api_in_silver( $url, $request, $method )
    {

        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Methods' => 'GET, POST, PUT, DELETE, OPTIONS',
            'Access-Control-Allow-Headers' => 'Origin, Content-Type, Accept, Authorization, X-Request-With',
            'Access-Control-Allow-Credentials' => 'true',
            'CUSTOM' => config('services.TOKEN_FOR_REQUESTS_TO_SILVER'),
        ])->{$method}($url, $request);
        
        return $response->json();

    }

    public function get_razones_sociales_from_silvertool_by_group_name( Request $request )
    {
        $validator = Validator::make($request->all(), [
            'empresa_id'  => 'required|integer|exists:empresas,id',
        ]);

        if ( $validator->fails() ) {
            return response()->json([
                'status'  => 'error',
                'message' => $validator->errors(),
            ], 400);
        }
        $empresa = Empresa::find( $request->get('empresa_id') );

        $data = [
            'group_name' => $empresa->nombre,
        ];

        $response = $this->send_data_to_api_in_silver( 
            config('services.PATH_TO_SILVERTOOL_DATABASE_MANAGER')."empresas/get_razones_sociales_by_group_name",
            $data,
            "get",
        );
        
        foreach ($response as $razon_social) {
            $razon_social_existente = RazonSocial::where('rut', $razon_social['SIRET'])->first();
            if ( $razon_social_existente ) {
                continue;
            }
            $nueva_razon_social = new RazonSocial();
            $nueva_razon_social->empresa_id          = $empresa->id;
            $nueva_razon_social->nombre              = $razon_social['RAISON_SOC'];
            $nueva_razon_social->rut                 = $razon_social['SIRET'];
            $nueva_razon_social->ciudad              = $razon_social['VILLE'];
            $nueva_razon_social->codigo_postal       = $razon_social['CODE_POSTAL'];
            
            foreach (config('razones_sociales') as $rut => $value) {
                if ( $nueva_razon_social->rut === $rut ) {
                    $nueva_razon_social->numero_de_cuenta_bancaria = $value['numero'];
                    $nueva_razon_social->banco                     = $value['banco'];
                    $nueva_razon_social->tipo_de_cuenta            = $value['tipo_de_cuenta'];
                }
            }
            $nueva_razon_social->save();
        }

        return redirect()->back()->with('success', "Las razones sociales se actualizaron correctamente");
    }

    public function get_razon_social_by_rut( Request $request )
    {
        $validator = Validator::make($request->all(), [
            'razon_social_id'  => 'required|integer|exists:razon_socials,id',
        ]);

        if ( $validator->fails() ) {
            return response()->json([
                'status'  => 'error',
                'message' => $validator->errors(),
            ], 400);
        }

        $razon_social = razonSocial::find( $request->get('razon_social_id') );

        $data = [
            'rut' => $razon_social->rut,
        ];

        $response = $this->send_data_to_api_in_silver( 
            config('services.PATH_TO_SILVERTOOL_DATABASE_MANAGER')."empresas/get_razon_social_by_rut",
            $data,
            "get",
        );

    }

    public function create_new_user( $razon_social )
    {
        $new_user = new User();
        $new_user->empresa_id = $razon_social->empresa->id;
        $new_user->name       = $razon_social->rut;
        $new_user->rut        = $razon_social->rut;
        $new_user->rol        = "Cliente";
        $new_user->password   = bcrypt($razon_social->rut);
        $new_user->save();
    }

    public function create_new_empresa( $name )
    {
        $empresa = new Empresa();
        $empresa->nombre = $name;
        $empresa->save();
        return $empresa;
    }

    public function register_razones_sociales( $empresa, $razones_sociales )
    {
        foreach ($razones_sociales as $razon_social) {
            $new_razon_social = $this->register_new_razon_social( $empresa, $razon_social );
            $this->register_related_data_to_razon_social( $new_razon_social, $razon_social );
        }
    }

    public function register_new_razon_social( $empresa, $razon_social )
    {
        $new_razon_social = new RazonSocial();
        $new_razon_social->empresa_id          = $empresa->id;
        $new_razon_social->nombre              = $razon_social['RAISON_SOC'];
        $new_razon_social->rut                 = $razon_social['SIRET'];
        $new_razon_social->ciudad              = $razon_social['VILLE'];
        $new_razon_social->codigo_postal       = $razon_social['CODE_POSTAL'];
        
        foreach (config('razones_sociales') as $rut => $value) {
            if ( $new_razon_social->rut === $rut ) {
                $new_razon_social->numero_de_cuenta_bancaria = $value['numero'];
                $new_razon_social->banco                     = $value['banco'];
                $new_razon_social->tipo_de_cuenta            = $value['tipo_de_cuenta'];
            }
        }
        
        if ( $razon_social['HEAD_OFFICE'] === "X" ) {
            $new_razon_social->principal = "true";
            $this->create_new_user( $new_razon_social );
        }
        $new_razon_social->save();

        return $new_razon_social;
    }

    public function register_related_data_to_razon_social( $new_razon_social, $razon_social )
    {
        foreach ($razon_social['missions'] as $mission) {
            if ( $mission ) {
                $new_mission = new Mission();
                $new_mission->razon_social_id    = $new_razon_social->id;
                $new_mission->COORDINATOR                = $mission['COORDINATOR'];
                $new_mission->CURRENT_STEP               = $mission['CURRENT_STEP'];
                $new_mission->DATE_DEBUT                 = $mission['DATE_DEBUT'];
                $new_mission->DATE_DEBUT_ANALYSE         = $mission['DATE_DEBUT_ANALYSE'];
                $new_mission->DATE_FIN_ANALYSE           = $mission['DATE_FIN_ANALYSE'];
                $new_mission->DATE_FIN_MISSION           = $mission['DATE_FIN_MISSION'];
                $new_mission->DEADLINE                   = $mission['DEADLINE'];
                $new_mission->FAMILLE                    = $mission['FAMILLE'];
                $new_mission->ID_MISSION                 = $mission['ID_MISSION'];
                $new_mission->NO_CONTRAT                 = $mission['NO_CONTRAT'];
                $new_mission->NO_MISSION                 = $mission['NO_MISSION'];
                $new_mission->PID_CONTRAT                = $mission['PID_CONTRAT'];
                $new_mission->PID_CONTRAT_DETAIL_PRODUIT = $mission['PID_CONTRAT_DETAIL_PRODUIT'];
                $new_mission->PID_IDENTIFICATION         = $mission['PID_IDENTIFICATION'];
                $new_mission->POURCENTAGE                = $mission['POURCENTAGE'];
                $new_mission->PRIORITY                   = $mission['PRIORITY'];
                $new_mission->PRODUIT                    = $mission['PRODUIT'];
                $new_mission->PROJECT_MANAGER            = $mission['PROJECT_MANAGER'];
                $new_mission->STEPS_MANAGED_FROM_MOTIVE  = $mission['STEPS_MANAGED_FROM_MOTIVE'];
                $new_mission->SYS_DATE_CREATION          = $mission['SYS_DATE_CREATION'];
                $new_mission->SYS_DATE_MODIFICATION      = $mission['SYS_DATE_MODIFICATION'];
                $new_mission->SYS_HEURE_CREATION         = $mission['SYS_HEURE_CREATION'];
                $new_mission->SYS_HEURE_MODIFICATION     = $mission['SYS_HEURE_MODIFICATION'];
                $new_mission->SYS_USER_CREATION          = $mission['SYS_USER_CREATION'];
                $new_mission->SYS_USER_MODIFICATION      = $mission['SYS_USER_MODIFICATION'];
                $new_mission->VP_FEES                    = $mission['VP_FEES'];
                $new_mission->VP_N_CONTRAT_CADRE         = $mission['VP_N_CONTRAT_CADRE'];
                $new_mission->VP_N_CONTRAT_PARTIEL       = $mission['VP_N_CONTRAT_PARTIEL'];
                $new_mission->VP_PRODUCT                 = $mission['VP_PRODUCT'];
                $new_mission->save();
                foreach ($mission['mission_motives'] as $mission_motive) {
                    if ( $mission_motive ) {
                        $new_motive = new MissionMotive();
                        $new_motive->mission_id     = $new_mission->id;
                        $new_motive->COMMENTS_SITE  = $mission_motive['COMMENTS_SITE'];
                        $new_motive->CONSULTANT     = $mission_motive['CONSULTANT'];
                        $new_motive->DATE_LIMITE    = $mission_motive['DATE_LIMITE'];
                        $new_motive->ETAPE_COURANTE = $mission_motive['ETAPE_COURANTE'];
                        $new_motive->MOTIF          = $mission_motive['MOTIF'];
                        $new_motive->POURCENTAGE    = $mission_motive['POURCENTAGE'];
                        $new_motive->save();

                        foreach ($mission_motive['mission_motive_ecos'] as $eco) {
                            if ( $eco ) {
                                $new_eco = new MissionMotiveEco();
                                $new_eco->mission_motive_id         = $new_motive->id;
                                $new_eco->mission_id                = $new_mission->id;
                                $new_eco->razon_social_id           = $new_razon_social->id;
                                $new_eco->mission_motive_id         = $new_motive->id;
                                $new_eco->DATE_PREVISIONNELLE       = $eco['DATE_PREVISIONNELLE'];
                                $new_eco->ECO_ABANDONNEE            = $eco['ECO_ABANDONNEE'];
                                $new_eco->ECO_A_FACTURER            = $eco['ECO_A_FACTURER'];
                                $new_eco->ECO_ECART                 = $eco['ECO_ECART'];
                                $new_eco->ECO_PRESENTEE             = $eco['ECO_PRESENTEE'];
                                $new_eco->ECO_VALIDEE               = $eco['ECO_VALIDEE'];
                                $new_eco->ID_MISSION_MOTIVE_ECO     = $eco['ID_MISSION_MOTIVE_ECO'];
                                $new_eco->NOTES                     = $eco['NOTES'];
                                $new_eco->PACKAGE                   = $eco['PACKAGE'];
                                $new_eco->PID_MISSION_MOTIVE        = $eco['PID_MISSION_MOTIVE'];
                                $new_eco->SELECTION_ECO_A_FACTURER  = $eco['SELECTION_ECO_A_FACTURER'];
                                $new_eco->SELECTION_ECO_VALIDEE     = $eco['SELECTION_ECO_VALIDEE'];
                                $new_eco->SELECTION_FACTURATION     = $eco['SELECTION_FACTURATION'];
                                $new_eco->SOUS_MOTIF_1              = $eco['SOUS_MOTIF_1'];
                                $new_eco->SOUS_MOTIF_1_FROM_MONTH   = $eco['SOUS_MOTIF_1_FROM_MONTH'];
                                $new_eco->SOUS_MOTIF_1_FROM_YEAR    = $eco['SOUS_MOTIF_1_FROM_YEAR'];
                                $new_eco->SOUS_MOTIF_1_TO_MONTH     = $eco['SOUS_MOTIF_1_TO_MONTH'];
                                $new_eco->SOUS_MOTIF_1_TO_YEAR      = $eco['SOUS_MOTIF_1_TO_YEAR'];
                                $new_eco->SOUS_MOTIF_2              = $eco['SOUS_MOTIF_2'];
                                $new_eco->SYS_DATE_CREATION         = $eco['SYS_DATE_CREATION'];
                                $new_eco->SYS_DATE_MODIFICATION     = $eco['SYS_DATE_MODIFICATION'];
                                $new_eco->SYS_HEURE_CREATION        = $eco['SYS_HEURE_CREATION'];
                                $new_eco->SYS_HEURE_MODIFICATION    = $eco['SYS_HEURE_MODIFICATION'];
                                $new_eco->SYS_USER_CREATION         = $eco['SYS_USER_CREATION'];
                                $new_eco->SYS_USER_MODIFICATION     = $eco['SYS_USER_MODIFICATION'];
                                $new_eco->YEAR                      = $eco['YEAR'];
                                $new_eco->CRITICITY                 = $eco['CRITICITY'];
                                $new_eco->save();

                                if ( $eco['invoice_ligne'] ) {
                                    $ligne = $eco['invoice_ligne'];
                                    $invoice_existente = Invoice::where('ID_INVOICE', $ligne['PID_INVOICE'])->first();
                                    if ( !$invoice_existente ) {
                                        $new_invoice = new Invoice();
                                        $new_invoice->razon_social_id        = $new_razon_social->id;
                                        $new_invoice->CONTRACT_NBER          = $ligne['invoice']['CONTRACT_NBER'];
                                        $new_invoice->DATE_EXPORT_SAGE       = $ligne['invoice']['DATE_EXPORT_SAGE'];
                                        $new_invoice->DUE_DATE               = $ligne['invoice']['DUE_DATE'];
                                        $new_invoice->ENTITY_NBER            = $ligne['invoice']['ENTITY_NBER'];
                                        $new_invoice->FIABILIS_GROUP_ENTITY  = $ligne['invoice']['FIABILIS_GROUP_ENTITY'];
                                        $new_invoice->ID_INVOICE             = $ligne['invoice']['ID_INVOICE'];
                                        $new_invoice->INVOICE_DATE           = $ligne['invoice']['INVOICE_DATE'];
                                        $new_invoice->INVOICE_NBER           = $ligne['invoice']['INVOICE_NBER'];
                                        $new_invoice->NO_CONTRAT             = $ligne['invoice']['NO_CONTRAT'];
                                        $new_invoice->PAYE                   = $ligne['invoice']['PAYE'];
                                        $new_invoice->PAYMENT_DATE           = $ligne['invoice']['PAYMENT_DATE'];
                                        $new_invoice->PID_CONTRAT            = $ligne['invoice']['PID_CONTRAT'];
                                        $new_invoice->PID_IDENTIFICATION     = $ligne['invoice']['PID_IDENTIFICATION'];
                                        $new_invoice->PID_INVOICE            = $ligne['invoice']['PID_INVOICE'];
                                        $new_invoice->PO                     = $ligne['invoice']['PO'];
                                        $new_invoice->PRODUCT                = $ligne['invoice']['PRODUCT'];
                                        $new_invoice->SELECTION_EXPORT       = $ligne['invoice']['SELECTION_EXPORT'];
                                        $new_invoice->STATUS                 = $ligne['invoice']['STATUS'];
                                        $new_invoice->SYS_DATE_CREATION      = $ligne['invoice']['SYS_DATE_CREATION'];
                                        $new_invoice->SYS_DATE_MODIFICATION  = $ligne['invoice']['SYS_DATE_MODIFICATION'];
                                        $new_invoice->SYS_HEURE_CREATION     = $ligne['invoice']['SYS_HEURE_CREATION'];
                                        $new_invoice->SYS_HEURE_MODIFICATION = $ligne['invoice']['SYS_HEURE_MODIFICATION'];
                                        $new_invoice->SYS_USER_CREATION      = $ligne['invoice']['SYS_USER_CREATION'];
                                        $new_invoice->SYS_USER_MODIFICATION  = $ligne['invoice']['SYS_USER_MODIFICATION'];
                                        $new_invoice->TOTAL_AMOUNT_INVOICED  = $ligne['invoice']['TOTAL_AMOUNT_INVOICED'];
                                        $new_invoice->TYPE                   = $ligne['invoice']['TYPE'];
                                        $new_invoice->BALANCE_DUE            = $ligne['invoice']['BALANCE_DUE'];
                                        $new_invoice->NOM_MODELE_WORD        = $ligne['invoice']['NOM_MODELE_WORD'];
                                        $new_invoice->save();
                                        $invoice_existente = $new_invoice;
                                    }

                                    $new_ligne = new InvoiceLigne();
                                    $new_ligne->AMOUNT                  = $ligne['AMOUNT'];
                                    $new_ligne->CN_CHOICE               = $ligne['CN_CHOICE'];
                                    $new_ligne->CN_ESTIMATED_DATE       = $ligne['CN_ESTIMATED_DATE'];
                                    $new_ligne->COMMENTAIRE             = $ligne['COMMENTAIRE'];
                                    $new_ligne->DISPLAY_NEW_FEE         = $ligne['DISPLAY_NEW_FEE'];
                                    $new_ligne->ECO_AMOUNT              = $ligne['ECO_AMOUNT'];
                                    $new_ligne->FEES                    = $ligne['FEES'];
                                    $new_ligne->FEE_INCLUDES_VAT        = $ligne['FEE_INCLUDES_VAT'];
                                    $new_ligne->ID_INVOICE_LIGNE        = $ligne['ID_INVOICE_LIGNE'];
                                    $new_ligne->MOTIVE                  = $ligne['MOTIVE'];
                                    $new_ligne->NO_LIGNE                = $ligne['NO_LIGNE'];
                                    $new_ligne->PID_INVOICE             = $ligne['PID_INVOICE'];
                                    $new_ligne->PID_INVOICE_LIGNE       = $ligne['PID_INVOICE_LIGNE'];
                                    $new_ligne->PID_MISSION_MOTIVE_ECO  = $ligne['PID_MISSION_MOTIVE_ECO'];
                                    $new_ligne->PRODUCT                 = $ligne['PRODUCT'];
                                    $new_ligne->SUB_MOTIVE1             = $ligne['SUB_MOTIVE1'];
                                    $new_ligne->SUB_MOTIVE2             = $ligne['SUB_MOTIVE2'];
                                    $new_ligne->SYS_DATE_CREATION       = $ligne['SYS_DATE_CREATION'];
                                    $new_ligne->SYS_DATE_MODIFICATION   = $ligne['SYS_DATE_MODIFICATION'];
                                    $new_ligne->SYS_HEURE_CREATION      = $ligne['SYS_HEURE_CREATION'];
                                    $new_ligne->SYS_HEURE_MODIFICATION  = $ligne['SYS_HEURE_MODIFICATION'];
                                    $new_ligne->SYS_USER_CREATION       = $ligne['SYS_USER_CREATION'];
                                    $new_ligne->SYS_USER_MODIFICATION   = $ligne['SYS_USER_MODIFICATION'];
                                    $new_ligne->TYPE                    = $ligne['TYPE'];
                                    $new_ligne->YEAR                    = $ligne['YEAR'];
                                    $new_ligne->invoice_id              = $invoice_existente->id;
                                    $new_ligne->mission_motive_eco_id   = $new_eco->id;
                                    $new_ligne->razon_social_id         = $new_razon_social->id;
                                    $new_ligne->mission_motive_id       = $new_motive->id;
                                    $new_ligne->save();
                                }
                                $this->register_new_gestion_first_time($new_eco);
                            }
                        }
                    }
                }
            }
        }
        return;
    }

    public function register_new_gestion_first_time($eco)
    {
        $monto_a_facturar = !$eco->invoice_ligne ? round(($eco->ECO_PRESENTEE * 0.3)) : null;

        $gestion = new Gestion();
        $gestion->mission_motive_eco_id = $eco->id;
        $gestion->mission_motive_id     = $eco->mission_motive->id;
        $gestion->mission_id            = $eco->mission->id;
        $gestion->razon_social_id       = $eco->razon_social_id;
        $gestion->motivo                = $eco->mission_motive->mission->PRODUIT;
        $gestion->gestion               = $eco->SOUS_MOTIF_2;
        $gestion->periodo_gestion       = self::convert_custom_string_to_date($eco->SOUS_MOTIF_1); // convertir en fecha
        $gestion->fecha_deposito        = $eco->DATE_PREVISIONNELLE;
        $gestion->monto_depositado      = $eco->ECO_PRESENTEE;
        $gestion->honorarios_fiabilis   = $eco->invoice_ligne ? round($eco->invoice_ligne->AMOUNT) : null;
        $gestion->montos_facturados     = $eco->invoice_ligne ? round($eco->invoice_ligne->AMOUNT) : null;
        $gestion->monto_a_facturar      = $monto_a_facturar;
        $gestion->origin                = "ST";
        $gestion->status                = $monto_a_facturar ? "Pendiente" : "Facturado";
        $gestion->save();
    }

    public static function convert_custom_string_to_date( $string )
    {
        if ( $string ) {
            $new_string = $string;
            $year = substr( $string, 0, 4 );
            $month = substr( $new_string, 4, 6 );
            // $format = 'Y-m-d';
            $date = $year."-".$month."-01";
            // $new_date = Carbon::createFromFormat($format, $input)->format('Y-m-d');
            $validator = Validator::make(['date' => $date], [
                'date'  => 'nullable|date_format:Y-m-d',
            ]);
    
            if ( $validator->fails() ) {
                $date = null;
            }
            return $date;
        }
        return null;
    }

    public function update_gestiones_from_silvertool( $scheduled_task = false )
    {
        $ecos = $this->get_datos_de_api( config('services.PATH_TO_SILVERTOOL_DATABASE_MANAGER')."empresas/get_ecos" );
        
        if ( !$ecos  ) {
            if ( $scheduled_task ) {
                return false;
            }
            return back()->with('error', "Hubo un error al momento de consultar la API: ".config('services.PATH_TO_SILVERTOOL_DATABASE_MANAGER')."empresas/get_ecos");
        }

        // $this->delete_gestiones_de_st();
        
        foreach ($ecos as $eco) {
            $rut = $this->get_rut( $eco );
            if ( !$rut ) {continue;}
            
            $razon_social = $this->check_if_razon_social_exists( $rut );
            if ( $razon_social ) {
                $this->update_data_in_razon_social( $eco, $razon_social );
                $this->handle_gestion( $eco, $razon_social );
            } else {
                // register new razon social
                $empresa_name = $this->get_empresa_name( $eco );
                if ( !$empresa_name ) {continue;} 
                $empresa = $this->get_empresa( $empresa_name );
                $new_razon_social = $this->register_new_razon_social( $empresa, $eco['mission_motive']['mission']['identification'] );
                $this->register_new_gestion( $eco, $new_razon_social );
            }
        }
        if ( $scheduled_task ) {
            return true;
        }
        return back()->with('success', "Gestiones actualizadas correctamente.");
    }

    public function get_empresa( $name )
    {
        $empresa = Empresa::where('nombre', $name)->first();
        if ( !$empresa ) {
            $empresa = $this->create_new_empresa( $name );
        }
        return $empresa;
    }

    public function get_empresa_name( $eco )
    {
        $name = null;
        if ( $eco ) { 
            if ( array_key_exists( 'mission_motive', $eco )) {
                if ( array_key_exists( 'mission', $eco['mission_motive'] ) ) {   
                    if ( array_key_exists( 'identification', $eco['mission_motive']['mission']) ) {
                        if ( array_key_exists( 'GROUP', $eco['mission_motive']['mission']['identification']) ) {           
                            $name = $eco['mission_motive']['mission']['identification']['GROUP'];
                        } 
                    }
                }
            }
        }
        return $name;
    }

    public function get_rut( $eco )
    {
        $rut = null;
        if ( $eco ) { 
            if ( array_key_exists( 'mission_motive', $eco )) {
                if ( array_key_exists( 'mission', $eco['mission_motive'] ) ) {   
                    if ( array_key_exists( 'identification', $eco['mission_motive']['mission']) ) {
                        if ( array_key_exists( 'SIRET', $eco['mission_motive']['mission']['identification']) ) {           
                            $rut = $eco['mission_motive']['mission']['identification']['SIRET'];
                        } 
                    }
                }
            }
        }
        return $rut;
    }

    public function check_if_razon_social_exists( $rut )
    {
        $razon_social = RazonSocial::where('rut', $rut)->first();
        if ( $razon_social ) {
            return $razon_social;
        }
        return false;
    }

    public function register_new_gestion( $eco, $razon_social )
    {
        $monto_a_facturar = !$eco['invoice_ligne'] ? round(($eco['ECO_PRESENTEE'] * 0.3)) : null;

        $gestion = new Gestion();
        $gestion->razon_social_id       = $razon_social->id;
        $gestion->ID_MISSION_MOTIVE_ECO = $eco['ID_MISSION_MOTIVE_ECO'];
        $gestion->motivo                = $eco['mission_motive']['mission']['PRODUIT'];
        $gestion->gestion               = $eco['SOUS_MOTIF_2'];
        $gestion->periodo_gestion       = self::convert_custom_string_to_date($eco['SOUS_MOTIF_1']); // convertir en fecha
        $gestion->fecha_deposito        = $eco['DATE_PREVISIONNELLE'];
        $gestion->monto_depositado      = $eco['ECO_PRESENTEE'];
        $gestion->honorarios_fiabilis   = $eco['invoice_ligne'] ? round($eco['invoice_ligne']['AMOUNT']) : null;
        $gestion->montos_facturados     = $eco['invoice_ligne'] ? round($eco['invoice_ligne']['AMOUNT']) : null;
        $gestion->monto_a_facturar      = $monto_a_facturar;
        $gestion->origin                = "ST";
        $gestion->status                = $monto_a_facturar ? "Pendiente" : "Facturado";
        $gestion->save();
    }

    public function delete_gestiones_de_st()
    {
        $ecos = Gestion::where('origin', 'ST')->get();

        foreach ($ecos as $eco) {
            if ( $eco ) {
                $eco->delete();
            }
        }
    }

    public function handle_gestion( $eco, $razon_social )
    {
        $gestion = Gestion::where('ID_MISSION_MOTIVE_ECO', $eco['ID_MISSION_MOTIVE_ECO'])->first();
        
        if ( !$gestion ) {
            $this->register_new_gestion( $eco, $razon_social );
            return;
        }

        // Update Gestion
        $monto_a_facturar = !$eco['invoice_ligne'] ? round(($eco['ECO_PRESENTEE'] * 0.3)) : null;

        $gestion->ID_MISSION_MOTIVE_ECO = $eco['ID_MISSION_MOTIVE_ECO'];
        $gestion->motivo                = $eco['mission_motive']['mission']['PRODUIT'];
        $gestion->gestion               = $eco['SOUS_MOTIF_2'];
        $gestion->periodo_gestion       = self::convert_custom_string_to_date($eco['SOUS_MOTIF_1']); // convertir en fecha
        $gestion->fecha_deposito        = $eco['DATE_PREVISIONNELLE'];
        $gestion->monto_depositado      = $eco['ECO_PRESENTEE'];
        $gestion->honorarios_fiabilis   = $eco['invoice_ligne'] ? round($eco['invoice_ligne']['AMOUNT']) : null;
        $gestion->montos_facturados     = $eco['invoice_ligne'] ? round($eco['invoice_ligne']['AMOUNT']) : null;
        $gestion->monto_a_facturar      = $monto_a_facturar;
        $gestion->status                = $monto_a_facturar ? "Pendiente" : "Facturado";
        $gestion->update();
        
        return;
    }

    public function update_data_in_razon_social( $eco, $razon_social )
    {
        return;
    }
}
