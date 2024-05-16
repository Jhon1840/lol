<!-- Modal de Confirmación para Cerrar Caja -->
<div class="modal fade" id="modalConfirmarCerrarCaja" tabindex="-1" aria-labelledby="modalConfirmarCerrarCajaLabel"
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
                <p>Dinero registrado por ventas: <span id="dineroEnCaja">{{ $dineroCajaAbierta }}</span> Bs</p>
                <input type="hidden" id="dineroEnCajaInput" value="{{ $dineroCajaAbierta }}">
                <div class="mb-3">
                    <label for="observaciones" class="form-label">Observaciones:</label>
                    <textarea class="form-control" id="observaciones" rows="3"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                <button type="button" class="btn btn-primary" id="confirmarCerrarCaja">Sí</button>
            </div>
        </div>
    </div>
</div>
