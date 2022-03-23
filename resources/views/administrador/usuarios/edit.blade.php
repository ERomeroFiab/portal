@extends('layouts.app')

@section('content')

    @include('includes.messages_in_session')
    
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h2>Usuarios<b>{{ $user->nombre }}</b></h2>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.usuarios.update', ['id' =>  $user->id]) }}" method="POST">
                        @csrf
                        <div class="row">

                            <div class="col-6">
                                <div class="form-group">
                                    <label for="name">Nombre</label>
                                    <input name="name" type="text" class="form-control" id="name" value="{{ $user->name }}">
                                </div>
                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input name="email" type="email" class="form-control" id="email" value="{{ $user->email }}">
                                </div>
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="empresa">Empresas</label>
                                    <select name="empresa_id" id="empresa" class="form-control">
                                        @foreach ($empresas as $empresa)
                                            <option value="{{$empresa->id}}">{{$empresa->nombre}}</option>
                                        @endforeach
                                        <option selected value="{{$user->empresa->id}}">{{$user->empresa->nombre}}</option>
                                    </select>
                                </div>
                                @error('empresa_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="col-12">
                                <input class="btn btn-sm btn-success" type="submit" value="Editar">
                            </div>

                        </div>
                    </form>
                </div>
            </div> <!-- End card -->
        </div>
    </div>
@endsection