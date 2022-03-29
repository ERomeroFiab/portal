@extends('layouts.app')

@section('customcss')
    <style>
        #tabla_gestiones_filter {
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
                    <h5>Gestiones</h5>
                </div>
                <div class="card-body">
                    <div class="row">

                        <div class="col-12">
                            <h6>Filtros:</h6>
                        </div>

                        <div class="col-3 form-group">
                            <label>Razones Sociales:</label>
                            <select id="input__razon_social" class="form-control">
                                <option value="">Todos</option>
                                @foreach ($razones_sociales as $razon_social)
                                    <option value="{{ $razon_social->id }}">{{ $razon_social->nombre }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-3 form-group">
                            <label>Rut:</label>
                            <input id="input__rut" type="text" class="form-control" autocomplete="off">
                        </div>

                        <div class="col-3 form-group">
                            <label>Gestión:</label>
                            <input class="form-control" list="gestiones" name="browser" id="input__gestion">
    
                            <datalist id="gestiones">
                                @foreach ($gestiones as $gestion)
                                    <option value="{{$gestion}}">{{$gestion}}</option>
                                @endforeach
                            </datalist>
                        </div>

                        <div class="col-3 form-group">
                            <label>Motivo:</label>
                            <input class="form-control" list="motivos" name="browser" id="input__motivo">
    
                            <datalist id="motivos">
                                @foreach ($motivos as $motivo)
                                    <option value="{{$motivo}}">{{$motivo}}</option>
                                @endforeach
                            </datalist>
                        </div>

                        <div class="col-12 form-group">
                            <button onclick="filtrar_tabla()" class="btn btn-sm btn-success" type="button">Filtrar Datos</button>
                        </div>

                    </div>
                    <div class="row mt-5">
                        <div class="col" style="overflow-x: auto;">
                            <table id="tabla_gestiones" class="table-hover table-striped table-bordered table-sm table-responsive" style="width:100%">
                                <thead class="table-header-fiabilis">
                                    <tr>
                                        <th>Razón Social</th>
                                        <th>Rut</th>
                                        <th>Motivo</th>
                                        <th>Gestion</th>
                                        <th>Periodo Gestión</th>
                                        <th>Fecha Depósito</th>
                                        <th>Banco</th>
                                        <th>Monto Depositado</th>
                                        <th>Honorarios Fiabilis</th>
                                        <th>Montos Facturados</th>
                                        <th>Monto a Facturar</th>
                                        {{-- <th class="no_exportar">&nbsp;</th> --}}
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
        let TABLA_GESTIONES;
        const CSRF = "{{ csrf_token() }}";
        const EMPRESA_NAME = "{{ auth()->user()->empresa->nombre }}";

        $(document).ready(function() {

            TABLA_GESTIONES = $('#tabla_gestiones').DataTable({
                serverSide: true,
                processing: true,
                ajax: {
                    url: "{{ route('ajax.get_tabla_gestiones_by_empresa') }}",
                    // error: function(jqXHR, ajaxOptions, thrownError) {
                    //     console.log("error: " + thrownError + "\n\n" + "status: " + jqXHR.statusText + "\n\n" + "response: "+jqXHR.responseText + "\n\n" + "options: "+ajaxOptions.responseText);
                    // },
                    data: function(d) {
                        d.search_by_empresa      = "{{ auth()->user()->empresa->id }}";
                        d.search_by_razon_social = document.querySelector('#input__razon_social').value;
                        d.search_by_rut          = document.querySelector('#input__rut').value;
                        d.search_by_gestion      = document.querySelector('#input__gestion').value;
                        d.search_by_motivo       = document.querySelector('#input__motivo').value;
                    }
                },
                columns: [
                    {data: "razon_social"},
                    {data: "rut"},
                    {data: "motivo"},
                    {data: "gestion"},
                    {data: "periodo_gestion"},
                    {data: "fecha_deposito"},
                    {data: "banco"},
                    {data: "monto_depositado"},
                    {data: "honorarios_fiabilis"},
                    {data: "montos_facturados"},
                    {data: "monto_a_facturar"},
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
                    title: `Gestiones de ${EMPRESA_NAME}`,
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
                dt.one('preXhr', function(e, s, data) {
                    // Just this once, load all data from the server...
                    data.start = 0;
                    data.length = 2147483647;
                    dt.one('preDraw', function(e, settings) {
                        // Call the original action function
                        if (button[0].className.indexOf('buttons-copy') >= 0) {
                            $.fn.dataTable.ext.buttons.copyHtml5.action.call(self, e, dt, button,
                                config);
                        } else if (button[0].className.indexOf('buttons-excel') >= 0) {
                            $.fn.dataTable.ext.buttons.excelHtml5.available(dt, config) ?
                                $.fn.dataTable.ext.buttons.excelHtml5.action.call(self, e, dt,
                                    button, config) :
                                $.fn.dataTable.ext.buttons.excelFlash.action.call(self, e, dt,
                                    button, config);
                        } else if (button[0].className.indexOf('buttons-csv') >= 0) {
                            $.fn.dataTable.ext.buttons.csvHtml5.available(dt, config) ?
                                $.fn.dataTable.ext.buttons.csvHtml5.action.call(self, e, dt, button,
                                    config) :
                                $.fn.dataTable.ext.buttons.csvFlash.action.call(self, e, dt, button,
                                    config);
                        } else if (button[0].className.indexOf('buttons-pdf') >= 0) {
                            $.fn.dataTable.ext.buttons.pdfHtml5.available(dt, config) ?
                                $.fn.dataTable.ext.buttons.pdfHtml5.action.call(self, e, dt, button,
                                    config) :
                                $.fn.dataTable.ext.buttons.pdfFlash.action.call(self, e, dt, button,
                                    config);
                        } else if (button[0].className.indexOf('buttons-print') >= 0) {
                            $.fn.dataTable.ext.buttons.print.action(e, dt, button, config);
                        }
                        dt.one('preXhr', function(e, s, data) {
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

        // function sweetAlert_to_remove_user(boton_submit) {
        //     Swal.fire({
        //         icon: "warning",
        //         title: '¿Desea ELIMINAR este usuario, con la empresa asociada y sus razones sociales?',
        //         confirmButtonText: `Eliminar`,
        //         confirmButtonColor: '#d9534f',
        //     }).then((result) => {
        //         /* Read more about isConfirmed, isDenied below */
        //         if (result.isConfirmed) {
        //             document.getElementById(boton_submit).click();
        //         }
        //     })
        // }

        function filtrar_tabla() {
            TABLA_GESTIONES.draw();
        }

        // Pintar en verde los inputs que contienen algo
        $("#input__razon_social").change(function() {
            agregar_quitar_bg_success('input__razon_social');
            filtrar_tabla();
        });
        $("#input__gestion").change(function() {
            agregar_quitar_bg_success('input__gestion');
            filtrar_tabla();
        });
        $("#input__motivo").change(function() {
            agregar_quitar_bg_success('input__motivo');
            filtrar_tabla();
        });
        $("#input__rut").change(function() {agregar_quitar_bg_success('input__rut');});

        function agregar_quitar_bg_success(id) {
            if ($(`#${id}`).val() !== "") {
                $(`#${id}`).addClass('bg-success');
            } else {
                $(`#${id}`).removeClass('bg-success');
            }
        }
    </script>
@endsection
