@extends('layouts.app')


@section('customcss')
    <style>
        @font-face {
    font-family: LeanOSans_FY_Regular;
    src: url('/fonts/LeanOSans_FY_Regular.eot');
}   
        
        #tabla_empresas_filter {
            display: none;
        }
        
    </style>
@endsection

@section('content')
<link rel="stylesheet" href="{{ URL::asset('css/bt.css') }}" />
    @include('includes.messages_in_session')

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5>Empresas</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-3 form-group">
                            <label>Nombre:</label>
                            <input id="input__Nombre" type="text" class="form-control">
                        </div>
                        <div class="col-3 form-group">
                            <label>Usuario:</label>
                            <input id="input__cliente" type="text" class="form-control">
                        </div>
                        <div class="col-3 form-group">
                            <label>Razones Sociales:</label>
                            <input id="input__razones_sociales_count" type="text" class="form-control">
                        </div>
                        <div class="col-3 form-group">
                            <label>Gestiones:</label>
                            <input id="input__gestiones_count" type="text" class="form-control">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <button class=" btn btn-sm btn-success float-right" type="button" onclick="buscar()">Buscar</button>
                        </div>
                    </div>
                        <div class="col-12">
                            <table id="tabla_empresas" class="table-hover" style="width:100%;">
                                <thead>
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Usuario</th>
                                        <th>Razones Sociales</th>
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
        let TABLA_EMPRESAS;
        const CSRF = "{{ csrf_token() }}";
        $(document).ready(function() {

            TABLA_EMPRESAS = $('#tabla_empresas').DataTable({
                serverSide: true,
                processing: true,
                ajax: {
                    url: "{{ route('ajax.get_tabla_empresas_as_consultor') }}",
                    // error: function(jqXHR, ajaxOptions, thrownError) {
                    //     console.log("error: " + thrownError + "\n\n" + "status: " + jqXHR.statusText + "\n\n" + "response: "+jqXHR.responseText + "\n\n" + "options: "+ajaxOptions.responseText);
                    // },
                    data: function ( d ) {
                        d.SEARCH_BY_NOMBRE                      = $('#input__Nombre').val();
                        d.SEARCH_BY_REPRESENTANTE               = $('#input__cliente').val();
                        d.SEARCH_BY_RAZONES_SOCIALES_COUNT      = $('#input__razones_sociales_count').val();
                        d.SEARCH_BY_GESTIONES_COUNT             = $('#input__gestiones_count').val();
                    }
                },
                columns: [
                    { 
                        data: "nombre", 
                        render: function (data, type, row){
                            return `<span title="empresa_id: ${data.id}">${data.nombre}</span>`;
                        }
                    },
                    { data: "representante"},
                    { data: "razones_sociales_count"},
                    { data: "gestiones_count"},
                    { 
                        data: 'action', 
                        render: function (data, type, row){
                            let html = "";
                            if ( data.path_to_show ) {
                                html += `<a href="${data.path_to_show}" class="bt_info btn btn-sm btn-info"><i class="fa-solid fa-eye"></i></a>`;
                            }
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
                    title: "Empresas del portal de clientes",
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

        function sweetAlert_to_remove_empresa( boton_submit ) {
            Swal.fire({
                icon: "warning",
                title: '¿Desea ELIMINAR esta empresa?',
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
            TABLA_EMPRESAS.draw();
        }

        // Pintar en verde los inputs que contienen algo
        $( "#input__Nombre" ).change(function() { agregar_quitar_bg_success('input__Nombre'); });
        $( "#input__cliente" ).change(function() { agregar_quitar_bg_success('input__cliente'); });
        $( "#input__razones_sociales_count" ).change(function() { agregar_quitar_bg_success('input__razones_sociales_count'); });
        $( "#input__gestiones_count" ).change(function() { agregar_quitar_bg_success('input__gestiones_count'); });

        function agregar_quitar_bg_success(id){
            if ( $(`#${id}`).val() !== "" ) {
                $(`#${id}`).addClass('bg-success');
            } else {
                $(`#${id}`).removeClass('bg-success');
            }
        }

    </script>
@endsection