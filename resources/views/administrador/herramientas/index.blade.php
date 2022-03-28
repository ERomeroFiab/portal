@extends('layouts.app')

@section('customcss')

@endsection

@section('content')

    @include('includes.messages_in_session')

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5>Herramientas</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('excel.import_excel_historico') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <div class="col-12">
                                <div class="col-12">
                                    <h6>Subir excel histórico (CN)</h6>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <p>Modelo del archivo aceptado (La hoja debe estar de primera):</p>
                            </div>
                            <div class="col-6">
                                <img class="img-fluid" src="{{ asset('imagen-modelo/seguimiento_produccion.png') }}" alt="Imagen modelo del excel aceptado">
                            </div>
                        </div>

                        <div class="row">

                            <div class="col-12">
                                <input name="file" type="file">
                                @error('file')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                                <input type="submit" class="btn btn-success" value="Subir Excel">
                            </div>
                        </div>

                    </form>
                </div>
            </div> <!-- End card -->
        </div>
    </div>

    

@endsection

@section('customjs')
    

@endsection