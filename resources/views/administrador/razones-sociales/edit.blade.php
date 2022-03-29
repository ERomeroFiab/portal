@extends('layouts.app')

@section('content')

    @include('includes.messages_in_session')
    
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5>Razon Social <b>{{ $razon_social->nombre }}</b></h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.razones-sociales.update', ['id' =>  $razon_social->id]) }}" method="POST">
                        @csrf
                        <div class="row">

                            <div class="col-6">
                                <div class="form-group">
                                    <label for="nombre">Nombre</label>
                                    <input name="nombre" type="text" class="form-control" id="nombre" value="{{ $razon_social->nombre }}">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="ciudad">Ciudad</label>
                                    <input name="ciudad" type="text" class="form-control" id="ciudad" value="{{ $razon_social->ciudad }}">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="codigo postal">Codigo Postal</label>
                                    <input name="codigo_postal" type="text" class="form-control" id="codigo_postal" value="{{ $razon_social->codigo_postal }}">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="direccion">Direccion</label>
                                    <input name="direccion" type="text" class="form-control" id="direccion" value="{{ $razon_social->direccion }}">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="numero_de_cuenta_bancaria">Numero de Cuenta Bancaria</label>
                                    <input name="numero_de_cuenta_bancaria" type="text" class="form-control" id="numero_de_cuenta_bancaria" value="{{ $razon_social->numero_de_cuenta_bancaria }}">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="banco">Banco</label>
                                    <input name="banco" type="text" class="form-control" id="banco" value="{{ $razon_social->banco }}">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="tipo_de_cuenta">Tipo de Cuenta</label>
                                    <input name="tipo_de_cuenta" type="text" class="form-control" id="tipo_de_cuenta" value="{{ $razon_social->tipo_de_cuenta }}">
                                </div>
                            </div>

                            <div class="col-12">
                                <input class="btn btn-sm btn-success" type="submit" value="Editar">
                            </div>
                            <div class="col-12">
                                <a href="{{ route('admin.razones-sociales.resetear_password', ['id' =>  $razon_social->id]) }}" class="btn btn-sm btn-success float-right">Resetear Contraseña</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div> <!-- End card -->
        </div>
    </div>
@endsection