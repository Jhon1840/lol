{{-- resources/views/components/tasks.blade.php --}}

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Registo de Apertura y cierre de cajas</h3>
        </div>
        <div class="table-responsive">
            <table class="table card-table table-vcenter">
                <thead>
                    <tr>
                        <th></th>
                        <th>Cajero</th>
                        <th>Fecha</th>
                        <th>Ventas</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($tasks as $task)
                        <tr>
                            <td class="w-1 pe-0">
                                <input type="checkbox" class="form-check-input m-0 align-middle" aria-label="Select task"
                                    {{ $task->estado == 'cerrada' ? 'checked' : '' }}>
                            </td>
                            <td class="w-100">
                                <a href="#" class="text-reset">{{ $task->nombre_vendedor }}</a>
                            </td>
                            <td class="text-nowrap text-secondary">{{ $task->fecha }}</td>
                            <td class="text-nowrap">{{ $task->dinero }}</td>
                            <td class="text-nowrap">{{ $task->estado }}</td>
                            <td>
                                <span class="avatar avatar-sm"
                                    style="background-image: url(./static/avatars/000m.jpg)"></span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
