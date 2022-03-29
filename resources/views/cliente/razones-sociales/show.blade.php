@extends('layouts.app')

@section('customcss')
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
                    <h5><b>{{ $razon_social->nombre }}</b></h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="name">Nombre</label>
                                <input disabled type="text" class="form-control" id="name"
                                    value="{{ $razon_social->nombre }}">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="rut">Rut</label>
                                <input disabled type="text" class="form-control" id="rut"
                                    value="{{ $razon_social->rut }}">
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <label for="contrato">Ciudad</label>
                                <input disabled type="text" class="form-control" id="contrato"
                                    value="{{ $razon_social->ciudad }}">
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <label for="date_signature">Codigo Postal</label>
                                <input disabled type="text" class="form-control" id="date_signature"
                                    value="{{ $razon_social->codigo_postal }}">
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <label for="date_signature">Banco</label>
                                <input disabled type="text" class="form-control" id="date_signature"
                                    value="{{ $razon_social->banco }}">
                            </div>
                        </div>


                    </div>

                    <div class="row mt-5">

                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12">
                                            <h6>Gestiones:</h6>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col" style="overflow-x: auto;">
                                            <table id="tabla_gestiones" class="table-hover table-striped table-bordered table-sm table-responsive" style="width:100%">
                                                <thead class="table-header-fiabilis">
                                                    <tr>
                                                        <th>Motivo</th>
                                                        <th>Gestion</th>
                                                        <th>Periodo Gestión</th>
                                                        <th>Fecha Depósito</th>
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
                            </div>
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
        const RAZON_SOCIAL_NAME = "{{ $razon_social->nombre }}";

        $(document).ready(function() {

            TABLA_GESTIONES = $('#tabla_gestiones').DataTable({
                serverSide: true,
                processing: true,
                ajax: {
                    url: "{{ route('ajax.get_tabla_gestiones_by_razon_social') }}",
                    // error: function(jqXHR, ajaxOptions, thrownError) {
                    //     console.log("error: " + thrownError + "\n\n" + "status: " + jqXHR.statusText + "\n\n" + "response: "+jqXHR.responseText + "\n\n" + "options: "+ajaxOptions.responseText);
                    // },
                    data: function(d) {
                        d.search_by_razon_social_id = "{{ $razon_social->id }}";
                    }
                },
                columns: [
                    {data: "motivo"},
                    {data: "gestion"},
                    {data: "periodo_gestion"},
                    {data: "fecha_deposito"},
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
                    title: `Gestiones de ${RAZON_SOCIAL_NAME}`,
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

        // function buscar() {
        //     TABLA_EMPRESAS.draw();
        // }

        // Pintar en verde los inputs que contienen algo
        // $("#input__ID_IDENTIFICATION").change(function() {
        //     agregar_quitar_bg_success('input__ID_IDENTIFICATION');
        // });

        function agregar_quitar_bg_success(id) {
            if ($(`#${id}`).val() !== "") {
                $(`#${id}`).addClass('bg-success');
            } else {
                $(`#${id}`).removeClass('bg-success');
            }
        }
    </script>
@endsection
