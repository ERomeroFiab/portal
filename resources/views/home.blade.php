@extends('layouts.app')

@section('custom_css')
    <style>
        #container {
            height: 400px;
        }

        .highcharts-figure,
        .highcharts-data-table table {
            min-width: 310px;
            max-width: 800px;
            margin: 1em auto;
        }

        .highcharts-data-table table {
            font-family: Verdana, sans-serif;
            border-collapse: collapse;
            border: 1px solid #ebebeb;
            margin: 10px auto;
            text-align: center;
            width: 100%;
            max-width: 500px;
        }

        .highcharts-data-table caption {
            padding: 1em 0;
            font-size: 1.2em;
            color: #555;
        }

        .highcharts-data-table th {
            font-weight: 600;
            padding: 0.5em;
        }

        .highcharts-data-table td,
        .highcharts-data-table th,
        .highcharts-data-table caption {
            padding: 0.5em;
        }

        .highcharts-data-table thead tr,
        .highcharts-data-table tr:nth-child(even) {
            background: #f8f8f8;
        }

        .highcharts-data-table tr:hover {
            background: #f1f7ff;
        }
    </style>
@endsection

@section('content')

    @include('includes.messages_in_session')

    <div class="row">
        <div class="col-lg-12">
            <div class="card">

                <div class="card-body">

                    @if ( count($gestiones) > 0 )

                        <div class="row">
                            <div class="col-12 text-center">
                                <div class="counter">
                                    <h3>Beneficios Obtenidos Desde Inicio del Proyecto:</h3>
                                    <h1 data-target="{{ $monto_depositado_total_en_numero }}" class="count text-success">0</h1>
                                </div>
                            </div>
                        </div>

                        <div class="row my-5">

                            <div class="col-lg-7 col-md-12">
                                <figure class="highcharts-figure">
                                    <div id="dona"></div>
                                </figure>
                            </div>

                            <div class="col-lg-5 col-md-12">
                                <figure class="highcharts-figure">
                                    <div id="columnas_chart"></div>
                                </figure>
                            </div>

                        </div>
                        
                        {{-- <div class="row mb-3">
                            <div class="col-12">
                                <h6>Gestiones</h6>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                @foreach ($gestiones as $gestion)
                                    <p><b>{{ $gestion['nombre'] }}</b>:</p>
                                    <p>Total: <b>{{ $gestion['total'] }}</b>, en ST: <b>{{ $gestion['total_st'] }}</b>, en CN: <b>{{ $gestion['total_cn'] }}</b></p>
                                    <hr>
                                @endforeach
                            </div>
                        </div> --}}
                    @else
                    <div class="row">
                        <div class="col-12">
                            <h5>Home</h5>
                        </div>
                    </div>
                    @endif


                </div>
            </div> <!-- End card -->
        </div>
    </div>
@endsection

@section('customjs')
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/highcharts-3d.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>

    <script>
        const GESTIONES_GRAFICO_DONA = JSON.parse({!! json_encode($gestiones_para_el_grafico_dona) !!});
        const GESTIONES_GRAFICO_COLUMNAS = JSON.parse({!! json_encode($gestiones_para_el_grafico_columnas) !!});
        const MONTO_DEPOSITADO_TOTAL = JSON.parse({!! json_encode($monto_depositado_total) !!});

        document.addEventListener('DOMContentLoaded', function () {

            Highcharts.chart('dona', {
                chart: {
                    type: 'pie',
                    options3d: {
                        enabled: true,
                        alpha: 45
                    },
                    style: {
                        fontFamily: '"Poppins", Verdana, sans-serif',
                    }
                },
                title: {
                    text: 'Beneficios por Gesti??n',
                    style: {
                        font: 'bold 18px "Poppins", Verdana, sans-serif'
                    }
                },
                subtitle: {
                    text: `Total: ${MONTO_DEPOSITADO_TOTAL} (100%)`,
                    style: {
                        font: '16px "Poppins", Verdana, sans-serif'
                    }
                },
                plotOptions: {
                    pie: {
                        innerSize: 100,
                        depth: 45
                    }
                },
                series: [
                    {
                        name: 'Porcentaje',
                        data: GESTIONES_GRAFICO_DONA
                    }
                ],
                exporting: {
                    enabled: false
                },
                credits: {
                    enabled: false
                },
            });


            Highcharts.chart('columnas_chart', {
                chart: {
                    type: 'column',
                    options3d: {
                        enabled: true,
                        alpha: 10,
                        beta: 25,
                        depth: 70
                    },
                    style: {
                        fontFamily: '"Poppins", Verdana, sans-serif',
                    }
                },
                title: {
                    text: 'Beneficios por A??o',
                    style: {
                        font: 'bold 18px "Poppins", Verdana, sans-serif'
                    }
                },
                subtitle: {
                    text: ''
                },
                plotOptions: {
                    column: {
                        depth: 25
                    }
                },
                xAxis: {
                    categories: GESTIONES_GRAFICO_COLUMNAS['categorias'],
                    labels: {
                        skew3d: true,
                        style: {
                            fontSize: '18px',
                        }
                    }
                },
                yAxis: {
                    title: {
                        text: null
                    }
                },
                series: [
                    {
                        name: 'Beneficios recibidos',
                        data: GESTIONES_GRAFICO_COLUMNAS['data']
                    }
                ],
                exporting: {
                    enabled: false
                },
                credits: {
                    enabled: false
                },
            });


            const counters = document.querySelectorAll(".count");
            const speed = 300;

            counters.forEach((counter) => {
                const updateCount = () => {
                    const target = parseInt(+counter.getAttribute("data-target"));
                    const count = parseInt(+counter.innerText);
                    const increment = Math.trunc(target / speed);

                    if (count < target) {
                        counter.innerText = count + increment;
                        setTimeout(updateCount, 1);
                    } else {
                        count.innerText = count;
                        counter.innerText = MONTO_DEPOSITADO_TOTAL[0];
                    }
                };
                updateCount();
            });
            
        });
    </script>
@endsection
