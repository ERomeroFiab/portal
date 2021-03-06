@extends('layouts.app')

@section('customcss')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection

@section('content')

    @include('includes.messages_in_session')


    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5>
                        Nuevo Usuario
                        <a href="{{ route('silver.update_database_first_time') }}">
                            <button onclick="$(this).prop('disabled', true).text('Actualizando...');" type="button" class="btn btn-sm btn-info float-right">
                                Actualizar la base de datos desde SilverTool
                            </button>
                        </a>
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <form action="{{ route('admin.usuarios.store') }}" method="POST">
                                @csrf

                                <div class="form-group">
                                    <label for="name">Nombre</label>
                                    <input name="name" type="text" class="form-control" id="name"
                                        aria-describedby="name_error" placeholder="Ejemplo: Juan" autocomplete="off" value="{{ old('name') }}">
                                    @if ($errors->has('name'))
                                        <small id="name_error"
                                            class="form-text text-muted text-danger">{{ $errors->first('name') }}</small>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input name="email" type="email" class="form-control" id="email"
                                        aria-describedby="email_error" placeholder="Ejemplo: juan@gmail.com" autocomplete="off" value="{{ old('email') }}">
                                    @if ($errors->has('email'))
                                        <small id="email_error"
                                            class="form-text text-muted text-danger">{{ $errors->first('email') }}</small>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <label for="rut">Rut</label>
                                    <input name="rut" type="text" class="form-control" id="rut"
                                        aria-describedby="rut_error" placeholder="Ejemplo: 12345678-9" autocomplete="off" value="{{ old('rut') }}">
                                    @if ($errors->has('rut'))
                                        <small id="rut_error" class="form-text text-muted text-danger">{{ $errors->first('rut') }}</small>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <label>Rol</label>
                                    <select name="rol" class="form-control">
                                        <option value="" selected disabled>-- Seleccione --</option>
                                        <option {{ old('rol') === "Cliente" ? "selected" : null  }} value="Cliente">Cliente</option>
                                        {{-- <option value="Gestor">Gestor</option> --}}
                                    </select>
                                    @if ($errors->has('rol'))
                                        <small id="rol_error"
                                            class="form-text text-muted text-danger">{{ $errors->first('rol') }}</small>
                                    @endif
                                </div>
                                
                                <div class="form-group">
                                    <label for="empresa">Empresa</label>
                                    <select name="empresa_id" class="js-example-basic-single form-control" id="empresa">
                                        <option value="" selected disabled>-- Seleccione --</option>
                                        @foreach ($empresas as $empresa)
                                            <option value="{{$empresa->id}}">{{$empresa->nombre}}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('empresa'))
                                        <small id="empresa_error" class="form-text text-muted text-danger">
                                            {{ $errors->first('empresa') }}
                                        </small>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">Guardar</button>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div> <!-- End card -->
        </div>
    </div>
@endsection

@section('customjs')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            $('.js-example-basic-single').select2();
        });
    </script>
@endsection