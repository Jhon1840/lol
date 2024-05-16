@extends('tablar::page')

@section('content')
    <!-- Page header -->
    <div class="page-body">
        <div class="container-xl">
            <div class="row row-deck row-cards">
                <!-- Columna para la tarjeta de totales -->
                <div class="col-md-6">
                    <div class="card card-sm">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <span class="bg-primary text-white avatar">
                                        <!-- SVG icon -->
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                            viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                            stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                            <path
                                                d="M16.7 8a3 3 0 0 0 -2.7 -2h-4a3 3 0 0 0 0 6h4a3 3 0 0 1 0 6h-4a3 3 0 0 1 -2.7 -2">
                                            </path>
                                            <path d="M12 3v3m0 12v3"></path>
                                        </svg>
                                    </span>
                                </div>
                                <div class="col">
                                    <div class="font-weight-medium">
                                        {{ $totalVentas }} Ventas
                                    </div>
                                    <div class="text-secondary">
                                        {{ number_format($totalRecaudado, 2) }}Bs Recolectados en total
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <!-- Page body -->
            <div class="page-body">
                <div class="container-xl">
                    <div class="row row-deck row-cards">
                        <!-- Columna para la tarjeta de totales -->


                        <!-- Columna para la tabla de productos más vendidos -->
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Productos Más Vendidos</h3>
                                </div>
                                <table class="table card-table table-vcenter">
                                    <thead>
                                        <tr>
                                            <th>Producto</th>
                                            <th>Cantidad Vendida</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($productosMasVendidos as $producto)
                                            <tr>
                                                <td>{{ $producto->Nombre }}</td>
                                                <td>{{ $producto->total_vendido }}</td>
                                                <td class="w-50">
                                                    <div class="progress progress-xs">
                                                        <div class="progress-bar bg-primary"
                                                            style="width: {{ ($producto->total_vendido / $maxVendidos) * 100 }}%">
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Columna para la tabla de productos que más dinero generan -->
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Productos Más Rentables</h3>
                                </div>
                                <table class="table card-table table-vcenter">
                                    <thead>
                                        <tr>
                                            <th>Producto</th>
                                            <th>Total Generado</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($productosMasRentables as $producto)
                                            <tr>
                                                <td>{{ $producto->Nombre }}</td>
                                                <td>${{ number_format($producto->total_generado, 2) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    @include('metricas.components.tasks')
                </div>

            </div>
        @endsection
