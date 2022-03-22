@extends('layouts.app')

@section('customcss')
    <style>
        #tabla_usuarios_filter {
            display: none;
        }
    </style>
@endsection

@section('content')

   @include('includes.messages_in_session')
   
   <div class="row">
      <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h2>Usuarios</h2>
                </div>
                <div class="card-body">
                   <div class="row">
                      <div class="col-12">
                         <table id="tabla_usuarios" class="table-hover" style="width:100%">
                           <thead>
                              <tr>
                                 <th>Nombre</th>
                                 <th>Email</th>
                                 <th>Rol</th>
                                 <th>Empresa</th>
                                 <th>&nbsp;</th>
                              </tr>
                           </thead>
                           <tbody>
                              {{-- SERVER SIDE RENDERING --}}
                           </tbody>
                        </table>
                     </div>
                  </div>
               </div>
            </div> <!-- End card -->
      </div>
   </div>
@endsection

@section('customjs')
    
    <script>
        let TABLA_USUARIOS;
        $(document).ready(function() {

            TABLA_USUARIOS = $('#tabla_usuarios').DataTable({
                serverSide: true,
                processing: true,
                ajax: {
                    url: "{{ route('ajax.get_tabla_usuarios') }}",
                    // error: function(jqXHR, ajaxOptions, thrownError) {
                    //     console.log("error: " + thrownError + "\n\n" + "status: " + jqXHR.statusText + "\n\n" + "response: "+jqXHR.responseText + "\n\n" + "options: "+ajaxOptions.responseText);
                    // },
                    data: function ( d ) {
                        // d.search_by_xxxx = $('#input__xxxx').val();
                    }
                },
                columns: [
                    { data: "name"},
                    { data: "email"},
                    { data: "rol"},
                    { data: "empresa"},
                    { 
                        data: 'action', 
                        render: function (data, type, row){
                            $html = "";
                            if ( data.path_to_show ) {
                                $html += `<a href="${data.path_to_show}" class="btn btn-sm btn-info">Ver</a>`;
                            }
                            if ( data.path_to_edit ) {
                                $html += `<a href="${data.path_to_edit}" class="btn btn-sm btn-warning">Editar</a>`;
                            }
                            return $html;
                        },
                        orderable: false, 
                        searchable: false
                    }
                ],
                // order: [[ 1, 'desc' ]],
                pageLength: 20,
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.10.19/i18n/Spanish.json',
                },
                dom: 'Bfrtip',
                buttons: [{
                    extend: 'excelHtml5',
                    title: "tabla empresas - " + new Date().toLocaleString(),
                    className: "bg-info",
                    exportOptions: {
                        columns: ':not(.no_exportar)'
                    },
                    action: newExportAction
                }],
            });

            // función para exportar el excel con todas las filas
            function newExportAction(e, dt, button, config) {
                var self = this;
                var oldStart = dt.settings()[0]._iDisplayStart;
                dt.one('preXhr', function (e, s, data) {
                    // Just this once, load all data from the server...
                    data.start = 0;
                    data.length = 2147483647;
                    dt.one('preDraw', function (e, settings) {
                        // Call the original action function
                        if (button[0].className.indexOf('buttons-copy') >= 0) {
                            $.fn.dataTable.ext.buttons.copyHtml5.action.call(self, e, dt, button, config);
                        } else if (button[0].className.indexOf('buttons-excel') >= 0) {
                            $.fn.dataTable.ext.buttons.excelHtml5.available(dt, config) ?
                                $.fn.dataTable.ext.buttons.excelHtml5.action.call(self, e, dt, button, config) :
                                $.fn.dataTable.ext.buttons.excelFlash.action.call(self, e, dt, button, config);
                        } else if (button[0].className.indexOf('buttons-csv') >= 0) {
                            $.fn.dataTable.ext.buttons.csvHtml5.available(dt, config) ?
                                $.fn.dataTable.ext.buttons.csvHtml5.action.call(self, e, dt, button, config) :
                                $.fn.dataTable.ext.buttons.csvFlash.action.call(self, e, dt, button, config);
                        } else if (button[0].className.indexOf('buttons-pdf') >= 0) {
                            $.fn.dataTable.ext.buttons.pdfHtml5.available(dt, config) ?
                                $.fn.dataTable.ext.buttons.pdfHtml5.action.call(self, e, dt, button, config) :
                                $.fn.dataTable.ext.buttons.pdfFlash.action.call(self, e, dt, button, config);
                        } else if (button[0].className.indexOf('buttons-print') >= 0) {
                            $.fn.dataTable.ext.buttons.print.action(e, dt, button, config);
                        }
                        dt.one('preXhr', function (e, s, data) {
                            // DataTables thinks the first item displayed is index 0, but we're not drawing that.
                            // Set the property to what it was before exporting.
                            settings._iDisplayStart = oldStart;
                            data.start = oldStart;
                        });
                        // Reload the grid with the original page. Otherwise, API functions like table.cell(this) don't work properly.
                        setTimeout(dt.ajax.reload, 0);
                        // Prevent rendering of the full data to the DOM
                        return false;
                    });
                });
                // Requery the server with the new one-time export settings
                dt.ajax.reload();
            }


        });

        function buscar(){
            TABLA_EMPRESAS.draw();
        }

        // Pintar en verde los inputs que contienen algo
        $( "#input__ID_IDENTIFICATION" ).change(function() { agregar_quitar_bg_success('input__ID_IDENTIFICATION'); });

        function agregar_quitar_bg_success(id){
            if ( $(`#${id}`).val() !== "" ) {
                $(`#${id}`).addClass('bg-success');
            } else {
                $(`#${id}`).removeClass('bg-success');
            }
        }

    </script>
@endsection
