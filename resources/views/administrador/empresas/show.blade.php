@extends('layouts.app')

@section('content')

    @include('includes.messages_in_session')
    
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h2>Empresa <b>{{ $empresa->nombre }}</b></h2>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="name">Nombre</label>
                                <input disabled type="text" class="form-control" id="name" value="{{ $empresa->nombre }}">
                            </div>
                        </div>

                        @if ( $empresa->representante )
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="tipo">Representante</label>
                                    <input disabled type="text" class="form-control" id="tipo" value="{{ $empresa->representante->name }}">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="tipo">Email</label>
                                    <input disabled type="text" class="form-control" id="tipo" value="{{ $empresa->representante->email }}">
                                </div>
                            </div>
                        @endif


                        <div class="col-12 mt-3">
                            <h6> {{ $empresa->get_razones_sociales_quantity_in_text() }}</h6>
                            <div class="row mt-5">
                                <div class="col-12">
                                    <div class="row">
                                        @foreach ($empresa->razones_sociales as $razon_social)
                                            <div class="col-3">
                                                <div class="card text-center">
                                                    <div class="card-header">
                                                        {{ $razon_social->nombre }}
                                                    </div>
                                                    <div class="card-body">
                                                        @if ( $razon_social->rut )
                                                            <p>Rut: <b>{{ $razon_social->rut }}</b></p>
                                                        @endif
                                                        @if ( $razon_social->ciudad )
                                                            <p>Ciudad: <b>{{ $razon_social->ciudad }}</b></p>
                                                        @endif
                                                        @if ( $razon_social->codigo_postal )
                                                            <p>Código postal: <b>{{ $razon_social->codigo_postal }}</b></p>
                                                        @endif
                                                        @if ( $razon_social->direccion )
                                                            <p>Dirección: <b>{{ $razon_social->direccion }}</b></p>
                                                        @endif
                                                        @if ( $razon_social->numero_de_cuenta_bancaria )
                                                            <p>N° Cuenta Bancaria: <b>{{ $razon_social->numero_de_cuenta_bancaria }}</b></p>
                                                        @endif
                                                        @if ( $razon_social->banco )
                                                            <p>Banco: <b>{{ $razon_social->banco }}</b></p>
                                                        @endif
                                                        @if ( $razon_social->tipo_de_cuenta )
                                                            <p>Cuenta: <b>{{ $razon_social->tipo_de_cuenta }}</b></p>
                                                        @endif
                                                        <a href="{{ route('admin.razones-sociales.show', ['id' => $razon_social->id]) }}" class="btn btn-sm btn-info">
                                                            Ver
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> <!-- End card -->
        </div>
    </div>
@endsection