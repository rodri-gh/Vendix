@extends('layouts.app')

@section('title', 'Panel')

@push('css')
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush

@section('content')

    @if (session('success'))
        <script>
            document.addEventListener("DOMContentLoaded", function() {

                let message = "{{ session('success') }}";
                Swal.fire(message);

            });
        </script>
    @endif

    <div class="container-fluid px-4">
        <h1 class="mt-4">Panel</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active">Panel</li>
        </ol>
        @hasrole('administrador')
            <div class="row justify-content-around">

                <!----Compra--->
                <div class="col-xl-3 col-md-6">
                    <div class="card bg-success text-white mb-4">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-8">
                                    <i class="fa-solid fa-store"></i><span class="m-1">Compras</span>
                                </div>
                                <div class="col-4">

                                    <p class="text-center fw-bold fs-4">{{ $compras }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer d-flex align-items-center justify-content-between">
                            <a class="small text-white stretched-link" href="{{ route('compras.index') }}">Ver más</a>
                            <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                        </div>
                    </div>
                </div>

                <!----Ventas--->
                <div class="col-xl-3 col-md-6">
                    <div class="card bg-primary text-white mb-4">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-8">
                                    <i class="fa-solid fa-people-group"></i><span class="m-1">Ventas</span>
                                </div>
                                <div class="col-4">

                                    <p class="text-center fw-bold fs-4">{{ $ventas }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer d-flex align-items-center justify-content-between">
                            <a class="small text-white stretched-link" href="{{ route('ventas.index') }}">Ver más</a>
                            <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                        </div>
                    </div>
                </div>

                <div class="row justify-content-center">
                    <div class="col-md-6 ">
                        <h2>Resumen del Mes</h2>
                        <p><strong>Ventas del mes:</strong> ${{ number_format($ventasMes, 2) }}</p>
                        <p><strong>Compras del mes:</strong> ${{ number_format($comprasMes, 2) }}</p>
                    </div>
                    <div class="col-md-6">
                        <h2>Resumen del Año</h2>
                        <p><strong>Ventas del año:</strong> ${{ number_format($ventasAnio, 2) }}</p>
                        <p><strong>Compras del año:</strong> ${{ number_format($comprasAnio, 2) }}</p>
                    </div>
                </div>
                <div id="chart"></div>


            </div>
        @else
            <h2 class="text-center">Bienvenid@ {{ auth()->user()->name }}</h2>
        @endhasrole
    </div>
@endsection

@push('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
    <!---script src="{{ asset('assets/demo/chart-area-demo.js') }}"></script--->
    <!---script src="{{ asset('assets/demo/chart-bar-demo.js') }}"></script--->
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" crossorigin="anonymous"></script>
    <script src="{{ asset('js/datatables-simple-demo.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var options = {
                series: [{
                    name: 'Ventas',
                    data: @json($ventasData)
                }, {
                    name: 'Compras',
                    data: @json($comprasData)
                }],
                chart: {
                    height: 350,
                    type: 'area'
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    curve: 'smooth'
                },
                xaxis: {
                    categories: @json(array_values($months)), // Pasamos los nombres de los meses
                    title: {
                        text: 'Meses'
                    }
                },
                tooltip: {
                    x: {
                        format: 'MMMM' // Esto asegura que se muestren los meses completos en el tooltip
                    }
                },
            };

            var chart = new ApexCharts(document.querySelector("#chart"), options);
            chart.render();
        });
    </script>
@endpush
