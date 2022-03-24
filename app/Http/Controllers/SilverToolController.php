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

class SilverToolController extends Controller
{
    public function update_database_first_time()
    {
        $empresas = $this->get_datos_de_api( config('services.PATH_TO_SILVERTOOL_DATABASE_MANAGER')."empresas/get_empresas_name" );
        foreach ($empresas as $empresa) {
            $new_empresa = $this->create_new_empresa( $empresa['name'] );
            $this->register_razones_sociales( $new_empresa, $empresa['razones_sociales'] );
        }

        return back()->with('success', "Base de datos actualizada correctamente.");
            
    }

    public function get_datos_de_api( $url )
    {

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

        dd( $response );
    }

    public function create_new_user( $razon_social )
    {
        $new_user = new User();
        $new_user->empresa_id = $razon_social->empresa->id;
        $new_user->name       = "Nombre";
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
            $this->register_new_missions( $new_razon_social, $razon_social );
            $this->register_new_invoices( $new_razon_social, $razon_social );
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

    public function register_new_missions( $new_razon_social, $razon_social )
    {
        foreach ($razon_social['missions'] as $mission) {
            if ( $mission ) {
                $new_mission = new Mission();
                $new_mission->razon_social_id    = $new_razon_social->id;
                $new_mission->COORDINATOR        = $mission['COORDINATOR'];
                $new_mission->CURRENT_STEP       = $mission['CURRENT_STEP'];
                $new_mission->DATE_DEBUT         = $mission['DATE_DEBUT'];
                $new_mission->DATE_DEBUT_ANALYSE = $mission['DATE_DEBUT_ANALYSE'];
                $new_mission->DATE_FIN_ANALYSE   = $mission['DATE_FIN_ANALYSE'];
                $new_mission->DATE_FIN_MISSION   = $mission['DATE_FIN_MISSION'];
                $new_mission->DEADLINE           = $mission['DEADLINE'];
                $new_mission->NO_CONTRAT         = $mission['NO_CONTRAT'];
                $new_mission->NO_MISSION         = $mission['NO_MISSION'];
                $new_mission->POURCENTAGE        = $mission['POURCENTAGE'];
                $new_mission->PRIORITY           = $mission['PRIORITY'];
                $new_mission->PRODUIT            = $mission['PRODUIT'];
                $new_mission->PROJECT_MANAGER    = $mission['PROJECT_MANAGER'];
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
                                $new_eco->NOTES                     = $eco['NOTES'];
                                $new_eco->PACKAGE                   = $eco['PACKAGE'];
                                $new_eco->SELECTION_ECO_A_FACTURER  = $eco['SELECTION_ECO_A_FACTURER'];
                                $new_eco->SELECTION_ECO_VALIDEE     = $eco['SELECTION_ECO_VALIDEE'];
                                $new_eco->SELECTION_FACTURATION     = $eco['SELECTION_FACTURATION'];
                                $new_eco->SOUS_MOTIF_1              = $eco['SOUS_MOTIF_1'];
                                $new_eco->SOUS_MOTIF_1_FROM_MONTH   = $eco['SOUS_MOTIF_1_FROM_MONTH'];
                                $new_eco->SOUS_MOTIF_1_FROM_YEAR    = $eco['SOUS_MOTIF_1_FROM_YEAR'];
                                $new_eco->SOUS_MOTIF_1_TO_MONTH     = $eco['SOUS_MOTIF_1_TO_MONTH'];
                                $new_eco->SOUS_MOTIF_1_TO_YEAR      = $eco['SOUS_MOTIF_1_TO_YEAR'];
                                $new_eco->SOUS_MOTIF_2              = $eco['SOUS_MOTIF_2'];
                                $new_eco->YEAR                      = $eco['YEAR'];
                                $new_eco->CRITICITY                 = $eco['CRITICITY'];
                                $new_eco->save();

                            }
                        }
                    }
                }
            }
        }
        return;
    }

    public function register_new_invoices( $new_razon_social, $razon_social )
    {
        foreach ($razon_social['invoices'] as $invoice) {
            if ( $invoice ) {
                $new_invoice = new Invoice();
                $new_invoice->razon_social_id       = $new_razon_social->id;
                $new_invoice->CONTRACT_NBER         = $invoice['CONTRACT_NBER'];
                $new_invoice->DATE_EXPORT_SAGE      = $invoice['DATE_EXPORT_SAGE'];
                $new_invoice->DUE_DATE              = $invoice['DUE_DATE'];
                $new_invoice->ENTITY_NBER           = $invoice['ENTITY_NBER'];
                $new_invoice->INVOICE_DATE          = $invoice['INVOICE_DATE'];
                $new_invoice->INVOICE_NBER          = $invoice['INVOICE_NBER'];
                $new_invoice->PAYE                  = $invoice['PAYE'];
                $new_invoice->PAYMENT_DATE          = $invoice['PAYMENT_DATE'];
                $new_invoice->PO                    = $invoice['PO'];
                $new_invoice->PRODUCT               = $invoice['PRODUCT'];
                $new_invoice->SELECTION_EXPORT      = $invoice['SELECTION_EXPORT'];
                $new_invoice->STATUS                = $invoice['STATUS'];
                $new_invoice->TOTAL_AMOUNT_INVOICED = $invoice['TOTAL_AMOUNT_INVOICED'];
                $new_invoice->TYPE                  = $invoice['TYPE'];
                $new_invoice->BALANCE_DUE           = $invoice['BALANCE_DUE'];
                $new_invoice->NOM_MODELE_WORD       = $invoice['NOM_MODELE_WORD'];
                $new_invoice->save();
            }
        }
        return;
    }
}
