@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h2>
                        Nuevo Usuario
                        <a href="{{ route('silver.actualizar_database') }}">
                            <button onclick="$(this).prop('disabled', true).text('Actualizando...');" type="button" class="btn btn-sm btn-info float-right">
                                Actualizar la Base de datos desde Silvertool
                            </button>
                        </a>
                    </h2>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <form action="{{ route('admin.usuarios.store') }}" method="POST">
                                @csrf

                                <div class="form-group">
                                    <label for="name">Nombre</label>
                                    <input name="name" type="text" class="form-control" id="name"
                                        aria-describedby="name_error" placeholder="Ejemplo: Juan">
                                    @if ($errors->has('name'))
                                        <small id="name_error"
                                            class="form-text text-muted text-danger">{{ $errors->first('name') }}</small>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input name="email" type="email" class="form-control" id="email"
                                        aria-describedby="email_error" placeholder="Ejemplo: juan@gmail.com">
                                    @if ($errors->has('email'))
                                        <small id="email_error"
                                            class="form-text text-muted text-danger">{{ $errors->first('email') }}</small>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <label for="rolInput">Rol</label>
                                    <select name="rol" class="form-control" id="rolInput">
                                        <option value="" selected disabled>-- Seleccione --</option>
                                        <option value="Cliente">Cliente</option>
                                        <option value="Gestor">Gestor</option>
                                    </select>
                                    @if ($errors->has('rol'))
                                        <small id="rol_error"
                                            class="form-text text-muted text-danger">{{ $errors->first('rol') }}</small>
                                    @endif
                                </div>

                                <div id="empresaInput" class="form-group d-none">
                                    <label for="empresa">Empresa</label>
                                    <select name="empresa" class="form-control" id="empresa">
                                        <option value="" selected disabled>-- Seleccione --</option>
                                        @foreach ($empresas as $empresa)
                                            <option value="{{$empresa->id}}">{{$empresa->nombre}}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('empresa'))
                                        <small id="empresa_error"
                                            class="form-text text-muted text-danger">{{ $errors->first('empresa') }}</small>
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
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let rolInput = document.querySelector('#rolInput');
            let empresaInput = document.querySelector('#empresaInput');
            rolInput.addEventListener('change', function(e){
                if ( e.target.value === "Cliente" ) {
                    // mostrar empresas
                    if ( empresaInput.classList.contains('d-none') ) {
                        empresaInput.classList.remove('d-none')
                    }
                } else {
                    // ocultar empresas
                    if ( !empresaInput.classList.contains('d-none') ) {
                        empresaInput.classList.add('d-none')
                        empresaInput.childNodes[3].value = "";
                    }
                }
            })
        });
    </script>
@endsection
