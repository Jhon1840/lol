<!-- resources/views/modals/modal_confirmar_cerrar_caja.blade.php -->
<div class="modal fade" id="modalConfirmacionCerrarCaja" tabindex="-1" aria-labelledby="modalConfirmarCerrarCajaLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalConfirmarCerrarCajaLabel">Cerrar Caja</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>¿Cerrar caja?</p>
                <p>Vendedor: {{ Auth::user()->name }}</p>
                <p>Fecha: {{ now()->toDateTimeString() }}</p>
                <form id="formCerrarCaja" method="POST" action="{{ route('ventas.cerrarCaja') }}">
                    @csrf
                    <input type="hidden" name="caja_id" value="{{ $cajaAbierta ? $cajaAbierta->id : '' }}">

                    <h5>Billetes recibidos:</h5>
                    @foreach ([200, 100, 50, 20, 10] as $billete)
                        <div class="form-group mb-2">
                            <label for="billete{{ $billete }}" class="form-label">Billetes de
                                Bs{{ $billete }}</label>
                            <input type="number" class="form-control" id="billete{{ $billete }}"
                                name="billetes[{{ $billete }}]" data-value="{{ $billete }}"
                                placeholder="Cantidad de billetes de Bs{{ $billete }}">
                        </div>
                    @endforeach

                    <h5>Monedas recibidas:</h5>
                    @foreach ([5, 2, 1, 0.5] as $moneda)
                        <div class="form-group mb-2">
                            <label for="moneda{{ str_replace('.', '', $moneda) }}" class="form-label">Monedas de
                                Bs{{ $moneda }}</label>
                            <input type="number" class="form-control" id="moneda{{ str_replace('.', '', $moneda) }}"
                                name="monedas[{{ $moneda }}]" data-value="{{ $moneda }}"
                                placeholder="Cantidad de monedas de Bs{{ $moneda }}">
                        </div>
                    @endforeach

                    <div class="form-group mb-3">
                        <label for="totalBilletesMonedas" class="form-label">Total Billetes y Monedas</label>
                        <input type="text" class="form-control" id="totalBilletesMonedas"
                            name="total_billetes_monedas" readonly>
                    </div>

                    <div class="mb-3">
                        <label for="observaciones" class="form-label">Observaciones:</label>
                        <textarea class="form-control" id="observaciones" name="observaciones" rows="3"></textarea>
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                <button type="submit" class="btn btn-primary" form="formCerrarCaja">Sí</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const inputsBilletesMonedas = document.querySelectorAll(
            '#modalConfirmacionCerrarCaja input[name^="billetes"], #modalConfirmacionCerrarCaja input[name^="monedas"]'
        );
        const totalBilletesMonedasInput = document.getElementById('totalBilletesMonedas');

        inputsBilletesMonedas.forEach(input => {
            input.addEventListener('input', calcularTotal);
        });

        function calcularTotal() {
            let total = 0;
            inputsBilletesMonedas.forEach(input => {
                const cantidad = parseInt(input.value) || 0;
                const denominacion = parseFloat(input.getAttribute('data-value'));
                total += cantidad * denominacion;
            });
            totalBilletesMonedasInput.value = total.toFixed(2);
        }
    });
</script>
