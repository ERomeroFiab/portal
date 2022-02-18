@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h2>Usuarios</h2>
                </div>
                <div class="card-body">
                   <div class="row">
                      <div class="col-12">
                         <table class="table-hover" style="width:100%">
                           <thead>
                              <tr>
                                 <th>Nombre</th>
                                 <th>Email</th>
                                 <th>Rol</th>
                                 <th>&nbsp;</th>
                              </tr>
                           </thead>
                           <tbody>
                              @foreach ($users as $user)
                                 <tr>
                                    <td>{{ $user->name }} </td>
                                    <td>{{ $user->email }} </td>
                                    <td>{{ $user->rol }} </td>
                                    <td> 
                                       <a href="{{ route('admin.usuarios.show', ['id' => $user->id]) }}" class="btn btn-sm btn-info">Ver Detalle</a>
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
