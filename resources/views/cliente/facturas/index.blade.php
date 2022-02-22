@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h2>{{ $cantidad_de_facturas < 2 ? $cantidad_de_facturas. " Servicio por facturar" : $cantidad_de_facturas. " Servicios por facturar" }}</h2>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <table class="table-hover" style="width:100%">
                            <thead>
                                <tr>
                                    <th>N°</th>
                                    <th>Monto</th>
                                    <th>fecha Limite</th>
                                    <th>Gestiones</th>
                                    <th>Razón Social</th>
                                    <th>Status</th>
                                    <th>&nbsp;</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($facturas as $factura)
                                <tr>
                                    <td>{{ $loop->iteration }} </td>
                                    <td>{{ $factura->monto }} </td>
                                    <td>-</td>
                                    <td>
                                        {{ count( $factura->gestiones ) }}
                                    </td>
                                    <td title="Empresa: {{ count( $factura->gestiones ) > 0 ? $factura->gestiones[0]->razon_social->empresa->nombre : "-" }}">
                                        {{ count( $factura->gestiones ) > 0 ? $factura->gestiones[0]->razon_social->nombre : "-" }}
                                    </td>
                                    <td>{{ $factura->status }} </td>
                                    <td>
                                        <a href="{{ route('cliente.facturas.show', ['id' => $factura->id]) }}" class="btn btn-sm btn-info">Ver</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div> <!-- End card -->
    </div>

</div>
@endsection