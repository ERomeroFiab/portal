<?php

namespace App\Imports;

use App\Models\Gestion;
use Maatwebsite\Excel\Concerns\ToModel;

// use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Events\AfterImport;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use App\Models\RazonSocial;
use App\Models\GestionesHistoricas;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class GestionesHistoricasImport implements ToModel, WithChunkReading, WithEvents, WithBatchInserts, WithCalculatedFormulas
{
    use Importable, RegistersEventListeners;

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $rut = $row[2];
        // Validaciones:
        if ( !str_contains($rut, "-") ) {return;}

        $razon_social_existente = RazonSocial::where( 'rut', $rut  )->first();

        if ( $razon_social_existente ) {
            $motivo              = $row[10];
            $gestion             = $row[9];
            $periodo_gestion     = $row[16];
            $fecha_deposito      = $row[22];
            $monto_depositado    = preg_replace("/[^0-9]/", "", round($row[19]));
            $honorarios_fiabilis = preg_replace("/[^0-9]/", "", round($row[20]));
            $montos_facturados   = preg_replace("/[^0-9]/", "", round($row[20]));
            $monto_a_facturar    = preg_replace("/[^0-9]/", "", round($row[78]));
            $origin              = "CN";

            $validator1 = Validator::make(['periodo_gestion' => $periodo_gestion], ['periodo_gestion'  => 'nullable|date_format:d-m-Y',]);
            if ( $validator1->fails() ) {$periodo_gestion = null;}
            if ( $periodo_gestion !== null ) {
                $periodo_gestion = Carbon::createFromFormat("d-m-Y", $periodo_gestion)->format('Y-m-d');
            }
            
            $validator2 = Validator::make(['fecha_deposito' => $fecha_deposito], ['fecha_deposito'  => 'nullable|date_format:d-m-Y',]);
            if ( $validator2->fails() ) {$fecha_deposito = null;}
            if ( $fecha_deposito !== null ) {
                $fecha_deposito = Carbon::createFromFormat("d-m-Y", $fecha_deposito)->format('Y-m-d');
            }

            $new_gestion = new Gestion();
            $new_gestion->motivo              = $motivo;
            $new_gestion->gestion             = $gestion;
            $new_gestion->periodo_gestion     = $periodo_gestion;
            $new_gestion->fecha_deposito      = $fecha_deposito;
            $new_gestion->monto_depositado    = $monto_depositado;
            $new_gestion->honorarios_fiabilis = $honorarios_fiabilis;
            $new_gestion->montos_facturados   = $montos_facturados;
            $new_gestion->monto_a_facturar    = $monto_a_facturar;
            $new_gestion->origin              = $origin;
            $new_gestion->razon_social_id     = $razon_social_existente->id;
            $new_gestion->status              = $monto_a_facturar ? "Pendiente" : "Facturado";
            $new_gestion->save();
            return;
        }
        return;
    
    }

    public function chunkSize(): int
    {
        return 5000;
    }

    public function batchSize(): int
    {
        return 5000;
    }

    public static function afterImport(AfterImport $event)
    {
        // $this_class = $event->getConcernable();
        // Log::info( json_encode( $this_class ) );
        // $proceso = EstadoProceso::find( $this_class->proceso_id );
        // $proceso->status = "Exitoso";
        // $proceso->update();
        // Log::info( json_encode($proceso->toArray()) );
    }
}
