@extends('layouts.app')

@section('customcss')
    <style>
        #tabla_razones_sociales_filter {
            display: none;
        }
    </style>
@endsection

@section('content')
<link rel="stylesheet" href="{{ URL::asset('css/bt_excel.css') }}" />

    @include('includes.messages_in_session')

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5>Razones Sociales</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-3 form-group">
                            <label>ID:</label>
                            <input id="input__id" type="text" class="form-control">
                        </div>
                        <div class="col-3 form-group">
                            <label>Empresa:</label>
                            <input id="input__empresa" type="text" class="form-control">
                        </div>
                        <div class="col-3 form-group">
                            <label>Nombre:</label>
                            <input id="input__nombre" type="text" class="form-control">
                        </div>
                        <div class="col-3 form-group">
                            <label>Rut:</label>
                            <input id="input__rut" type="text" class="form-control">
                        </div>
                        <div class="col-3 form-group">
                            <label>Ciudad:</label>
                            <input id="input__ciudad" type="text" class="form-control">
                        </div>
                        <div class="col-3 form-group">
                            <label>Codigo Postal:</label>
                            <input id="input__codigo_postal" type="text" class="form-control">
                        </div>
                        <div class="col-3 form-group">
                            <label>Direccion:</label>
                            <input id="input__direccion" type="text" class="form-control">
                        </div>
                        <div class="col-3 form-group">
                            <label>Numero de Cuenta Bancaria:</label>
                            <input id="input__numero_de_cuenta_bancaria" type="text" class="form-control">
                        </div>
                        <div class="col-3 form-group">
                            <label>Banco:</label>
                            <input id="input__banco" type="text" class="form-control">
                        </div>
                        <div class="col-3 form-group">
                            <label>Tipo de Cuenta:</label>
                            <input id="input__tipo_de_cuenta" type="text" class="form-control">
                        </div>
                        <div class="col-3 form-group">
                            <label>Gestiones:</label>
                            <input id="input__gestiones" type="text" class="form-control">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <button class="btn btn-sm btn-success float-right" type="button" onclick="buscar()">Buscar</button>
                        </div>
                    </div>
                        <div class="col" style="overflow-x:auto;">
                            <table id="tabla_razones_sociales" class="table-hover" style="width:100%;">
                                <thead class="table-header-fiabilis">
                                    <tr>
                                        <th>id</th>
                                        <th>empresa</th>
                                        <th>nombre</th>
                                        <th>rut</th>
                                        <th>ciudad</th>
                                        <th>codigo_postal</th>
                                        <th>direccion</th>
                                        <th>numero_de_cuenta_bancaria</th>
                                        <th>banco</th>
                                        <th>tipo_de_cuenta</th>
                                        <th>principal</th>
                                        <th>Gestiones</th>
                                        <th class="no_exportar">&nbsp;</th>
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
        let TABLA_RAZONES_SOCIALES;
        const CSRF = "{{ csrf_token() }}";

        $(document).ready(function() {

            TABLA_RAZONES_SOCIALES = $('#tabla_razones_sociales').DataTable({
                serverSide: true,
                processing: true,
                ajax: {
                    url: "{{ route('ajax.get_tabla_razones_sociales') }}",
                    // error: function(jqXHR, ajaxOptions, thrownError) {
                    //     console.log("error: " + thrownError + "\n\n" + "status: " + jqXHR.statusText + "\n\n" + "response: "+jqXHR.responseText + "\n\n" + "options: "+ajaxOptions.responseText);
                    // },
                    data: function ( d ) {
                        // d.search_by_xxxx = $('#input__xxxx').val();
                        d.SEARCH_BY_ID                      = $('#input__id').val();
                        d.SEARCH_BY_EMPRESA                 = $('#input__empresa').val();
                        d.SEARCH_BY_NOMBRE                  = $('#input__nombre').val();
                        d.SEARCH_BY_RUT                     = $('#input__rut').val();
                        d.SEARCH_BY_CIUDAD                  = $('#input__ciudad').val();
                        d.SEARCH_BY_CODIGO_POSTAL           = $('#input__codigo_postal').val();
                        d.SEARCH_BY_DIRECCION               = $('#input__direccion').val();
                        d.SEARCH_BY_NUMERO_DE_CUENTA_BANCARIA  = $('#input__numero_de_cuenta_bancaria').val();
                        d.SEARCH_BY_BANCO                   = $('#input__banco').val();
                        d.SEARCH_BY_TIPO_DE_CUENTA          = $('#input__tipo_de_cuenta').val();
                        d.SEARCH_BY_GESTIONES               = $('#input__gestiones').val();
                    }
                },
                columns: [
                    { data: "id"},
                    { data: "empresa"},
                    { data: "nombre"},
                    { data: "rut"},
                    { data: "ciudad"},
                    { data: "codigo_postal"},
                    { data: "direccion"},
                    { data: "numero_de_cuenta_bancaria"},
                    { data: "banco"},
                    { data: "tipo_de_cuenta"},
                    { data: "principal"},
                    { data: "gestiones_count"},
                    { 
                        data: 'action', 
                        render: function (data, type, row){
                            let html = "";
                            if ( data.path_to_show ) {
                                html += `<a href="${data.path_to_show}" class="btn btn-sm btn-info"><i class="fa-solid fa-eye"></i></a>`;
                            }
                            /*if ( data.path_to_edit ) {
                                html += `<a href="${data.path_to_edit}" class="btn btn-sm btn-warning"><i class="fa-solid fa-pen-to-square"></i></a>`;
                            }*/
                            return html;
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
                    text: "Exportar a Excel",
                    title: "Razones sociales - " + new Date().toLocaleString(),
                    className: "bt_excel",
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

        function sweetAlert_to_remove_razon_social( boton_submit ) {
            Swal.fire({
                icon: "warning",
                title: '¿Desea ELIMINAR esta Razón Social?',
                confirmButtonText: `Eliminar`,
                confirmButtonColor: '#d9534f',
            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    document.getElementById(boton_submit).click();
                }
            })
        }

        function buscar(){
            TABLA_RAZONES_SOCIALES.draw();
        }

        // Pintar en verde los inputs que contienen algo
        $( "#input__id" ).change(function() { agregar_quitar_bg_success('input__id'); });
        $( "#input__empresa" ).change(function() { agregar_quitar_bg_success('input__empresa'); });
        $( "#input__nombre" ).change(function() { agregar_quitar_bg_success('input__nombre'); });
        $( "#input__rut" ).change(function() { agregar_quitar_bg_success('input__rut'); });
        $( "#input__ciudad" ).change(function() { agregar_quitar_bg_success('input__ciudad'); });
        $( "#input__codigo_postal" ).change(function() { agregar_quitar_bg_success('input__codigo_postal'); });
        $( "#input__direccion" ).change(function() { agregar_quitar_bg_success('input__direccion'); });
        $( "#input__numero_de_cuenta_bancaria" ).change(function() { agregar_quitar_bg_success('input__numero_de_cuenta_bancaria'); });
        $( "#input__banco" ).change(function() { agregar_quitar_bg_success('input__banco'); });
        $( "#input__tipo_de_cuenta" ).change(function() { agregar_quitar_bg_success('input__tipo_de_cuenta'); });
        $( "#input__gestiones" ).change(function() { agregar_quitar_bg_success('input__gestiones'); });


        function agregar_quitar_bg_success(id){
            if ( $(`#${id}`).val() !== "" ) {
                $(`#${id}`).addClass('bg-success');
            } else {
                $(`#${id}`).removeClass('bg-success');
            }
        }

    </script>
@endsection