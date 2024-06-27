@extends('tablar::page')

@section('content')
    <div class="container">
        <h1>Lista de Cierres de Caja</h1>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Archivo</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($cajas as $caja)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ basename($caja->url) }}</td>
                            <td>
                                <a href="{{ $caja->url }}" target="_blank" class="btn btn-primary btn-sm">
                                    Descargar
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3">No hay cierres de caja disponibles.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
