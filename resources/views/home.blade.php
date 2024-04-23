@extends('tablar::page')
@section('content')
    <!-- Page header -->

    <!-- Page body -->
    <div class="page-body">
        <div class="container-xl">
            <div class="row row-deck row-cards">
                <div class="col-12">
                    <?php $products = App\Models\Product::all(); ?>

                    <x-card>
                        @role('admin')
                            <x-slot name="header">
                                <h3 class="card-title">Productos y su PPP</h3>
                            </x-slot>
                        @endrole
                        <x-slot name="body">
                            <div class="card-body">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Producto</th>
                                            <th>PPP</th>
                                            <th>Precio Compra</th>
                                            <th>Stock</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($products as $product)
                                            <tr>
                                                <td>{{ $product->Nombre }}</td>
                                                <td>
                                                    @php
                                                        $precioCompra = intval($product->Precio_compra);
                                                        $stock = intval($product->stock);
                                                        $PPP =
                                                            $stock != 0
                                                                ? number_format($precioCompra / $stock, 2)
                                                                : 'N/A';
                                                        echo $PPP;
                                                    @endphp
                                                </td>
                                                <td>{{ $product->Precio_compra }}</td>
                                                <td>{{ $product->stock }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </x-slot>
                    </x-card>

                </div>



            </div>
        </div>
    </div>
@endsection
