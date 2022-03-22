@extends('layouts.app')

@section('customcss')

@endsection

@section('content')

    @include('includes.messages_in_session')

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h2>Herramientas</h2>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                           <a href="#" class="btn btn-sm btn-info">
                               Registrar nuevos clientes desde SilverTool
                           </a>
                        </div>
                    </div>
                </div>
            </div> <!-- End card -->
        </div>
    </div>

    

@endsection

@section('customjs')
    

@endsection