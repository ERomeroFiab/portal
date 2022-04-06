@extends('layouts.app')

@section('customcss')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        #tabla_gestiones_filter {
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
                    <h5>Servicios Por Cobrar</h5>
                </div>
                <div class="card-body">
                    <div class="row">

                        <div class="col-12">
                            <h6>Filtros:</h6>
                        </div>

                        <div class="col-3 form-group">
                            <label>Razón Social:</label>
                            <input id="input__razon_social" type="text" class="form-control" autocomplete="off">
                        </div>

                        <div class="col-3 form-group">
                            <label>Rut:</label>
                            <input id="input__rut" type="text" class="form-control" autocomplete="off">
                        </div>

                        <div class="col-3 form-group">
                            <label>Motivo</label>
                            <select id="input__motivo" class="js-example-basic-single form-control">
                                <option value="" selected disabled>-- Seleccione --</option>
                                <option value="">TODOS</option>
                                @foreach ($motivos as $motivo)
                                    <option value="{{$motivo}}">{{$motivo}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-3 form-group">
                            <label>Gestión</label>
                            <select id="input__gestion" class="js-example-basic-single form-control">
                                <option value="" selected disabled>-- Seleccione --</option>
                                <option value="">TODOS</option>
                                @foreach ($gestiones as $gestion)
                                    <option value="{{$gestion}}">{{$gestion}}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- <div class="col-3 form-group">
                            <label>Periodo Gestión Desde:</label>
                            <input id="input__periodo_gestion_desde" type="date" class="form-control" autocomplete="off" min="1999-01-01">
                        </div>

                        <div class="col-3 form-group">
                            <label>Periodo Gestión Hasta:</label>
                            <input id="input__periodo_gestion_hasta" type="date" class="form-control" autocomplete="off" min="1999-01-01">
                        </div> --}}

                        {{-- <div class="col-3 form-group">
                            <label>Fecha Depósito Desde:</label>
                            <input id="input__periodo_depositado_desde" type="date" class="form-control" autocomplete="off" min="1999-01-01">
                        </div>

                        <div class="col-3 form-group">
                            <label>Fecha Depósito Hasta:</label>
                            <input id="input__periodo_depositado_hasta" type="date" class="form-control" autocomplete="off" min="1999-01-01">
                        </div> --}}

                        {{-- <div class="col-3 form-group">
                            <label>Banco:</label>
                            <input id="input__banco" type="text" class="form-control" autocomplete="off">
                        </div> --}}

                        <div class="col-3 form-group">
                            <label>Monto Depositado:</label>
                            <input id="input__monto_depositado" type="number" class="form-control" autocomplete="off" min="0">
                        </div>

                        <div class="col-3 form-group">
                            <label>Honorarios Fiabilis:</label>
                            <input id="input__honorarios_fiabilis" type="number" class="form-control" autocomplete="off" min="0">
                        </div>

                        <div class="col-3 form-group">
                            <label>Montos Facturados:</label>
                            <input id="input__montos_facturados" type="number" class="form-control" autocomplete="off" min="0">
                        </div>

                        <div class="col-3 form-group">
                            <label>Monto a Facturar:</label>
                            <input id="input__monto_a_facturar" type="number" class="form-control" autocomplete="off" min="0">
                        </div>

                        <div class="col-3 form-group">
                            <label>Estado</label>
                            <select id="input__status" class="form-control">
                                <option value="" selected disabled>-- Seleccione --</option>
                                <option value="">TODOS</option>
                                <option value="Facturado">Facturado</option>
                                <option value="Pendiente">Pendiente</option>
                            </select>
                        </div>


                        <div class="col-12 form-group">
                            <button onclick="filtrar_tabla()" class="btn btn-sm btn-success float-right" type="button">Filtrar Datos</button>
                        </div>

                    </div>
                    <div class="row mt-5">
                        <div class="col" style="overflow-x: auto;">
                            <table id="tabla_gestiones" class="table-hover table-striped table-bordered table-sm table-responsive" style="width:100%">
                                <thead class="table-header-fiabilis">
                                    <tr>
                                        <th>Razón Social</th>
                                        <th class="min_width_120_class">Rut</th>
                                        <th>Motivo</th>
                                        <th>Gestión</th>
                                        <th class="min_width_120_class">Periodo Gestión</th>
                                        <th class="min_width_120_class">Fecha Depósito</th>
                                        <th>Monto Depositado</th>
                                        <th>Honorarios Fiabilis</th>
                                        <th>Montos Facturados</th>
                                        <th>Monto a Facturar</th>
                                        <th>Estado</th>
                                        <th>Origen</th>
                                        <th class="d-none">Banco</th>
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
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        let TABLA_GESTIONES;
        const CSRF = "{{ csrf_token() }}";

        $(document).ready(function() {

            $('.js-example-basic-single').select2();

            TABLA_GESTIONES = $('#tabla_gestiones').DataTable({
                serverSide: true,
                processing: true,
                ajax: {
                    url: "{{ route('ajax.get_tabla_servicios_por_cobrar_as_consultor') }}",
                    // error: function(jqXHR, ajaxOptions, thrownError) {
                    //     console.log("error: " + thrownError + "\n\n" + "status: " + jqXHR.statusText + "\n\n" + "response: "+jqXHR.responseText + "\n\n" + "options: "+ajaxOptions.responseText);
                    // },
                    data: function(d) {
                        d.search_by_razon_social                     = document.querySelector('#input__razon_social').value;
                        d.search_by_rut                              = document.querySelector('#input__rut').value;
                        d.search_by_gestion                          = document.querySelector('#input__gestion').value;
                        d.search_by_motivo                           = document.querySelector('#input__motivo').value;
                        // d.search_by_periodo_gestion_desde            = document.querySelector('#input__periodo_gestion_desde').value;
                        // d.search_by_periodo_gestion_hasta            = document.querySelector('#input__periodo_gestion_hasta').value;
                        // d.search_by_periodo_depositado_desde         = document.querySelector('#input__periodo_depositado_desde').value;
                        // d.search_by_periodo_depositado_hasta         = document.querySelector('#input__periodo_depositado_hasta').value;
                        // d.search_by_banco                            = document.querySelector('#input__banco').value;
                        d.search_by_monto_depositado                 = document.querySelector('#input__monto_depositado').value;
                        d.search_by_honorarios_fiabilis              = document.querySelector('#input__honorarios_fiabilis').value;
                        d.search_by_montos_facturados                = document.querySelector('#input__montos_facturados').value;
                        d.search_by_monto_a_facturar                 = document.querySelector('#input__monto_a_facturar').value;
                        d.search_by_status                           = document.querySelector('#input__status').value;
                    }
                },
                columns: [
                    {data: "razon_social"},
                    {data: "rut"},
                    {data: "motivo"},
                    {data: "gestion"},
                    {data: "periodo_gestion"},
                    {data: "fecha_deposito"},
                    {data: "monto_depositado"},
                    {data: "honorarios_fiabilis"},
                    {data: "montos_facturados"},
                    {data: "monto_a_facturar"},
                    {data: "status"},
                    {data: "origin"},
                    {data: "banco", class: "d-none"},
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
                    title: `Servicios por cobrar`,
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
            agregar_quitar_bg_success_select2('input__gestion');
            filtrar_tabla();
        });
        $("#input__motivo").change(function() {
            agregar_quitar_bg_success_select2('input__motivo');
            filtrar_tabla();
        });

        $("#input__periodo_gestion_desde").change(function() {
            agregar_quitar_bg_success('input__periodo_gestion_desde');
            filtrar_tabla();
        });
        
        $("#input__periodo_gestion_hasta").change(function() {
            agregar_quitar_bg_success('input__periodo_gestion_hasta');
            filtrar_tabla();
        });

        $("#input__periodo_depositado_desde").change(function() {
            agregar_quitar_bg_success('input__periodo_depositado_desde');
            filtrar_tabla();
        });

        $("#input__periodo_depositado_hasta").change(function() {
            agregar_quitar_bg_success('input__periodo_depositado_hasta');
            filtrar_tabla();
        });

        $("#input__rut").change(function() {agregar_quitar_bg_success('input__rut');});
        $("#input__banco").change(function() {agregar_quitar_bg_success('input__banco');});
        $("#input__monto_depositado").change(function() {agregar_quitar_bg_success('input__monto_depositado');});
        $("#input__honorarios_fiabilis").change(function() {agregar_quitar_bg_success('input__honorarios_fiabilis');});
        $("#input__montos_facturados").change(function() {agregar_quitar_bg_success('input__montos_facturados');});
        $("#input__monto_a_facturar").change(function() {agregar_quitar_bg_success('input__monto_a_facturar');});

        $("#input__status").change(function() {
            agregar_quitar_bg_success('input__status');
            filtrar_tabla();
        });



        function agregar_quitar_bg_success(id) {
            if ($(`#${id}`).val() !== "") {
                $(`#${id}`).addClass('bg-success');
            } else {
                $(`#${id}`).removeClass('bg-success');
            }
        }

        function agregar_quitar_bg_success_select2(id) {
            if ($(`#${id}`).val() !== "") {
                $(`#${id}`).next().children().children().children().addClass('bg-success')
            } else {
                $(`#${id}`).next().children().children().children().removeClass('bg-success')
            }
        }
    </script>
@endsection
