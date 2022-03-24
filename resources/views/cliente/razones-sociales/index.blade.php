@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ URL::asset('css/card.css') }}" />
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-12 mt-3">
                        <h5>Razones Sociales</h5>
                        
                    </div>
                </div>
                <div class="row">
                    @foreach ($razones_sociales as $razon_social)
                        <div class="col-3 my-3">
                            <div class="inside_card" style="cursor: pointer" >
                                <div class="card-header">
                                    <b>{{ $razon_social->nombre }}</b>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <a href="{{route('cliente.razones-sociales.show', ['id' => $razon_social->id])}}">
                                            <div class="col-12" style="color: black ">
                                                <p>Rut: <b>{{ $razon_social->rut ?? "-" }}</b></p>
                                                <p>Ciudad: <b>{{ $razon_social->ciudad ?? "-" }}</b></p>
                                                <p>Código Postal: <b>{{ $razon_social->codigo_postal ?? "-" }}</b></p>
                                                <p>Dirección: <b>{{ $razon_social->direccion ?? "-" }}</b></p>
                                                <p>N° de Cuenta: <b>{{ $razon_social->numero_de_cuenta_bancaria ?? "-" }}</b></p>
                                                <p>Banco: <b>{{ $razon_social->banco ?? "-" }}</b></p>
                                                <p>Cuenta: <b>{{ $razon_social->tipo_de_cuenta ?? "-" }}</b></p>
                                            </div>
                                        </a>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                </div>
            </div>
        </div> <!-- End card -->
    </div>
</div>
@endsection