@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h2>Usuario <b>{{ $user->name }}</b></h2>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <form>

                                <div class="form-group">
                                    <label for="name">Nombre</label>
                                    <input disabled type="text" class="form-control" id="name" value="{{ $user->name }}">
                                </div>

                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input disabled type="text" class="form-control" id="email"
                                        value="{{ $user->email }}">
                                </div>

                                <div class="form-group">
                                    <label for="rolInput">Rol</label>
                                    <input disabled type="text" class="form-control" id="rolInput"
                                        value="{{ $user->rol }}">
                                </div>
                                
                                @if ( $user->empresa )
                                    <div id="empresaInput" class="form-group">
                                        <label for="rolInput">Empresa</label>
                                        <input disabled type="text" class="form-control" id="rolInput" value="{{ $user->empresa->nombre }}">
                                    </div>
                                @endif


                            </form>
                        </div>
                    </div>
                </div>
            </div> <!-- End card -->
        </div>
    </div>
@endsection
