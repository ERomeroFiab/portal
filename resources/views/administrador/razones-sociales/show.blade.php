@extends('layouts.app')

@section('customcss')
    <style>
        #tabla_missions_filter {
            display: none;
        }
        #tabla_motives_filter {
            display: none;
        }
        #tabla_ecos_filter {
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
                    <h5>Razón Social <b>{{ $razon_social->nombre }}</b> (id: {{$razon_social->id}})</h5>
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
                        <div class="col-6">
                            <div class="form-group">
                                <label for="contrato">Ciudad</label>
                                <input disabled type="text" class="form-control" id="contrato"
                                    value="{{ $razon_social->ciudad }}">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="date_signature">Codigo Postal</label>
                                <input disabled type="text" class="form-control" id="date_signature"
                                    value="{{ $razon_social->codigo_postal }}">
                            </div>
                        </div>


                    </div>

                    <div class="row mt-5">

                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12">
                                            <h6>Missions:</h6>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col" style="overflow-x:auto;">
                                            <table id="tabla_missions" class="table-hover" style="width:100%">
                                                <thead>
                                                    <tr>
                                                        <th>id</th>
                                                        <th>razon_social_id</th>
                                                        <th>COORDINATOR</th>
                                                        <th>CURRENT_STEP</th>
                                                        <th>DATE_DEBUT</th>
                                                        <th>DATE_DEBUT_ANALYSE</th>
                                                        <th>DATE_FIN_ANALYSE</th>
                                                        <th>DATE_FIN_MISSION</th>
                                                        <th>DEADLINE</th>
                                                        <th>NO_CONTRAT</th>
                                                        <th>NO_MISSION</th>
                                                        <th>POURCENTAGE</th>
                                                        <th>PRIORITY</th>
                                                        <th>PRODUIT</th>
                                                        <th>PROJECT_MANAGER</th>
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

                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12" style="overflow-x:auto;">
                                            <h6>Motivos:</h6>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col" style="overflow-x:auto;">
                                            <table id="tabla_motives" class="table-hover" style="width:100%">
                                                <thead>
                                                    <tr>
                                                        <th>id</th>
                                                        <th>mission_id</th>
                                                        <th>COMMENTS_SITE</th>
                                                        <th>CONSULTANT</th>
                                                        <th>DATE_LIMITE</th>
                                                        <th>ETAPE_COURANTE</th>
                                                        <th>ID_MISSION_MOTIVE</th>
                                                        <th>MOTIF</th>
                                                        <th>PID_MISSION</th>
                                                        <th>POURCENTAGE</th>
                                                        <th>SYS_DATE_CREATION</th>
                                                        <th>SYS_DATE_MODIFICATION</th>
                                                        <th>SYS_HEURE_CREATION</th>
                                                        <th>SYS_HEURE_MODIFICATION</th>
                                                        <th>SYS_USER_CREATION</th>
                                                        <th>SYS_USER_MODIFICATION</th>
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

                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12">
                                            <h6>Ecos:</h6>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col" style="overflow-x:auto;">
                                            <table id="tabla_ecos" class="table-hover" style="width:100%">
                                                <thead>
                                                    <tr>
                                                        <th>id</th>
                                                        <th>motive_id</th>
                                                        <th>mission_id</th>
                                                        <th>razon_social_id</th>
                                                        <th>DATE_PREVISIONNELLE</th>
                                                        <th>ECO_ABANDONNEE</th>
                                                        <th>ECO_A_FACTURER</th>
                                                        <th>ECO_ECART</th>
                                                        <th>ECO_PRESENTEE</th>
                                                        <th>ECO_VALIDEE</th>
                                                        <th>ID_MISSION_MOTIVE_ECO</th>
                                                        <th>NOTES</th>
                                                        <th>PACKAGE</th>
                                                        <th>PID_MISSION_MOTIVE</th>
                                                        <th>SELECTION_ECO_A_FACTURER</th>
                                                        <th>SELECTION_ECO_VALIDEE</th>
                                                        <th>SELECTION_FACTURATION</th>
                                                        <th>SOUS_MOTIF_1</th>
                                                        <th>SOUS_MOTIF_1_FROM_MONTH</th>
                                                        <th>SOUS_MOTIF_1_FROM_YEAR</th>
                                                        <th>SOUS_MOTIF_1_TO_MONTH</th>
                                                        <th>SOUS_MOTIF_1_TO_YEAR</th>
                                                        <th>SOUS_MOTIF_2</th>
                                                        <th>SYS_DATE_CREATION</th>
                                                        <th>SYS_DATE_MODIFICATION</th>
                                                        <th>SYS_HEURE_CREATION</th>
                                                        <th>SYS_HEURE_MODIFICATION</th>
                                                        <th>SYS_USER_CREATION</th>
                                                        <th>SYS_USER_MODIFICATION</th>
                                                        <th>TMP_NO_INVOICE</th>
                                                        <th>YEAR</th>
                                                        <th>CRITICITY</th>
                                                        <th>TIME</th>
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

                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12">
                                            <h6>Invoices:</h6>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col" style="overflow-x:auto;">
                                            <table id="tabla_invoices" class="table-hover" style="width:100%">
                                                <thead>
                                                    <tr>
                                                        <th>id</th>
                                                        <th>razon_social_id</th>
                                                        <th>CONTRACT_NBER</th>
                                                        <th>DATE_EXPORT_SAGE</th>
                                                        <th>DUE_DATE</th>
                                                        <th>ENTITY_NBER</th>
                                                        <th>FIABILIS_GROUP_ENTITY</th>
                                                        <th>ID_INVOICE</th>
                                                        <th>INVOICE_DATE</th>
                                                        <th>INVOICE_NBER</th>
                                                        <th>NO_CONTRAT</th>
                                                        <th>PAYE</th>
                                                        <th>PAYMENT_DATE</th>
                                                        <th>PID_CONTRAT</th>
                                                        <th>PID_IDENTIFICATION</th>
                                                        <th>PID_INVOICE</th>
                                                        <th>PO</th>
                                                        <th>PRODUCT</th>
                                                        <th>SELECTION_EXPORT</th>
                                                        <th>STATUS</th>
                                                        <th>SYS_DATE_CREATION</th>
                                                        <th>SYS_DATE_MODIFICATION</th>
                                                        <th>SYS_HEURE_CREATION</th>
                                                        <th>SYS_HEURE_MODIFICATION</th>
                                                        <th>SYS_USER_CREATION</th>
                                                        <th>SYS_USER_MODIFICATION</th>
                                                        <th>TOTAL_AMOUNT_INVOICED</th>
                                                        <th>TYPE</th>
                                                        <th>BALANCE_DUE</th>
                                                        <th>NOM_MODELE_WORD</th>
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
        let TABLA_MISSIONS;
        let TABLA_MOTIVES;
        let TABLA_ECOS;
        let TABLA_INVOICES;
        const CSRF = "{{ csrf_token() }}";

        $(document).ready(function() {

            TABLA_MISSIONS = $('#tabla_missions').DataTable({
                serverSide: true,
                processing: true,
                ajax: {
                    url: "{{ route('ajax.get_tabla_missions') }}",
                    // error: function(jqXHR, ajaxOptions, thrownError) {
                    //     console.log("error: " + thrownError + "\n\n" + "status: " + jqXHR.statusText + "\n\n" + "response: "+jqXHR.responseText + "\n\n" + "options: "+ajaxOptions.responseText);
                    // },
                    data: function(d) {
                        d.search_by_razon_social_id = "{{ $razon_social->id }}";
                    }
                },
                columns: [
                    {data: "id"},
                    {data: "razon_social_id"},
                    {data: "COORDINATOR"},
                    {data: "CURRENT_STEP"},
                    {data: "DATE_DEBUT"},
                    {data: "DATE_DEBUT_ANALYSE"},
                    {data: "DATE_FIN_ANALYSE"},
                    {data: "DATE_FIN_MISSION"},
                    {data: "DEADLINE"},
                    {data: "NO_CONTRAT"},
                    {data: "NO_MISSION"},
                    {data: "POURCENTAGE"},
                    {data: "PRIORITY"},
                    {data: "PRODUIT"},
                    {data: "PROJECT_MANAGER"},
                ],
                // order: [[ 1, 'desc' ]],
                pageLength: 20,
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.10.19/i18n/Spanish.json',
                },
                dom: 'Bfrtip',
                buttons: [{
                    extend: 'excelHtml5',
                    title: "tabla missions - " + new Date().toLocaleString(),
                    className: "bg-info",
                    exportOptions: {
                        columns: ':not(.no_exportar)'
                    },
                    action: newExportAction
                }],
            });

            TABLA_MOTIVES = $('#tabla_motives').DataTable({
                serverSide: true,
                processing: true,
                ajax: {
                    url: "{{ route('ajax.get_tabla_motives') }}",
                    // error: function(jqXHR, ajaxOptions, thrownError) {
                    //     console.log("error: " + thrownError + "\n\n" + "status: " + jqXHR.statusText + "\n\n" + "response: "+jqXHR.responseText + "\n\n" + "options: "+ajaxOptions.responseText);
                    // },
                    data: function(d) {
                        d.search_by_razon_social_id = "{{ $razon_social->id }}";
                    }
                },
                columns: [
                    {data: "id"},
                    {data: "mission_id"},
                    {data: "COMMENTS_SITE"},
                    {data: "CONSULTANT"},
                    {data: "DATE_LIMITE"},
                    {data: "ETAPE_COURANTE"},
                    {data: "ID_MISSION_MOTIVE"},
                    {data: "MOTIF"},
                    {data: "PID_MISSION"},
                    {data: "POURCENTAGE"},
                    {data: "SYS_DATE_CREATION"},
                    {data: "SYS_DATE_MODIFICATION"},
                    {data: "SYS_HEURE_CREATION"},
                    {data: "SYS_HEURE_MODIFICATION"},
                    {data: "SYS_USER_CREATION"},
                    {data: "SYS_USER_MODIFICATION"},
                ],
                // order: [[ 1, 'desc' ]],
                pageLength: 20,
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.10.19/i18n/Spanish.json',
                },
                dom: 'Bfrtip',
                buttons: [{
                    extend: 'excelHtml5',
                    title: "tabla motives - " + new Date().toLocaleString(),
                    className: "bg-info",
                    exportOptions: {
                        columns: ':not(.no_exportar)'
                    },
                    action: newExportAction
                }],
            });

            TABLA_ECOS = $('#tabla_ecos').DataTable({
                serverSide: true,
                processing: true,
                ajax: {
                    url: "{{ route('ajax.get_tabla_ecos') }}",
                    // error: function(jqXHR, ajaxOptions, thrownError) {
                    //     console.log("error: " + thrownError + "\n\n" + "status: " + jqXHR.statusText + "\n\n" + "response: "+jqXHR.responseText + "\n\n" + "options: "+ajaxOptions.responseText);
                    // },
                    data: function(d) {
                        d.search_by_razon_social_id = "{{ $razon_social->id }}";
                    }
                },
                columns: [
                    {data: "id"},
                    {data: "mission_motive_id"},
                    {data: "mission_id"},
                    {data: "razon_social_id"},
                    {data: "DATE_PREVISIONNELLE"},
                    {data: "ECO_ABANDONNEE"},
                    {data: "ECO_A_FACTURER"},
                    {data: "ECO_ECART"},
                    {data: "ECO_PRESENTEE"},
                    {data: "ECO_VALIDEE"},
                    {data: "ID_MISSION_MOTIVE_ECO"},
                    {data: "NOTES"},
                    {data: "PACKAGE"},
                    {data: "PID_MISSION_MOTIVE"},
                    {data: "SELECTION_ECO_A_FACTURER"},
                    {data: "SELECTION_ECO_VALIDEE"},
                    {data: "SELECTION_FACTURATION"},
                    {data: "SOUS_MOTIF_1"},
                    {data: "SOUS_MOTIF_1_FROM_MONTH"},
                    {data: "SOUS_MOTIF_1_FROM_YEAR"},
                    {data: "SOUS_MOTIF_1_TO_MONTH"},
                    {data: "SOUS_MOTIF_1_TO_YEAR"},
                    {data: "SOUS_MOTIF_2"},
                    {data: "SYS_DATE_CREATION"},
                    {data: "SYS_DATE_MODIFICATION"},
                    {data: "SYS_HEURE_CREATION"},
                    {data: "SYS_HEURE_MODIFICATION"},
                    {data: "SYS_USER_CREATION"},
                    {data: "SYS_USER_MODIFICATION"},
                    {data: "TMP_NO_INVOICE"},
                    {data: "YEAR"},
                    {data: "CRITICITY"},
                    {data: "TIME"},
                ],
                // order: [[ 1, 'desc' ]],
                pageLength: 20,
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.10.19/i18n/Spanish.json',
                },
                dom: 'Bfrtip',
                buttons: [{
                    extend: 'excelHtml5',
                    title: "tabla ecos - " + new Date().toLocaleString(),
                    className: "bg-info",
                    exportOptions: {
                        columns: ':not(.no_exportar)'
                    },
                    action: newExportAction
                }],
            });

            TABLA_INVOICES = $('#tabla_invoices').DataTable({
                serverSide: true,
                processing: true,
                ajax: {
                    url: "{{ route('ajax.get_tabla_invoices') }}",
                    // error: function(jqXHR, ajaxOptions, thrownError) {
                    //     console.log("error: " + thrownError + "\n\n" + "status: " + jqXHR.statusText + "\n\n" + "response: "+jqXHR.responseText + "\n\n" + "options: "+ajaxOptions.responseText);
                    // },
                    data: function(d) {
                        d.search_by_razon_social_id = "{{ $razon_social->id }}";
                    }
                },
                columns: [
                    {data: "id"},
                    {data: "razon_social_id"},
                    {data: "CONTRACT_NBER"},
                    {data: "DATE_EXPORT_SAGE"},
                    {data: "DUE_DATE"},
                    {data: "ENTITY_NBER"},
                    {data: "FIABILIS_GROUP_ENTITY"},
                    {data: "ID_INVOICE"},
                    {data: "INVOICE_DATE"},
                    {data: "INVOICE_NBER"},
                    {data: "NO_CONTRAT"},
                    {data: "PAYE"},
                    {data: "PAYMENT_DATE"},
                    {data: "PID_CONTRAT"},
                    {data: "PID_IDENTIFICATION"},
                    {data: "PID_INVOICE"},
                    {data: "PO"},
                    {data: "PRODUCT"},
                    {data: "SELECTION_EXPORT"},
                    {data: "STATUS"},
                    {data: "SYS_DATE_CREATION"},
                    {data: "SYS_DATE_MODIFICATION"},
                    {data: "SYS_HEURE_CREATION"},
                    {data: "SYS_HEURE_MODIFICATION"},
                    {data: "SYS_USER_CREATION"},
                    {data: "SYS_USER_MODIFICATION"},
                    {data: "TOTAL_AMOUNT_INVOICED"},
                    {data: "TYPE"},
                    {data: "BALANCE_DUE"},
                    {data: "NOM_MODELE_WORD"},
                ],
                // order: [[ 1, 'desc' ]],
                pageLength: 20,
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.10.19/i18n/Spanish.json',
                },
                dom: 'Bfrtip',
                buttons: [{
                    extend: 'excelHtml5',
                    title: "tabla invoices - " + new Date().toLocaleString(),
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
