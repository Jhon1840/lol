@extends('tablar::page')

@section('content')
    <div class="page-body">
        <div class="container-xl">
            
            <div class="row row-deck row-cards">
                
                @include('metricas.components.total_ventas')
            </div>

            <div class="page-body">
                <div class="container-xl">
                    <div class="row row-deck row-cards">
                        @include('metricas.components.productos_mas_vendidos')
                        @include('metricas.components.productos_mas_rentables')
                    </div>

                    <div class="row row-deck row-cards">
                        @include('metricas.components.tasks')
                        @include('metricas.components.productos_menor_stock')
                    </div>

                    <div class="row row-deck row-cards">
                        @include('metricas.components.ventas_canceladas')
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
