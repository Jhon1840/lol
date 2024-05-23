<!-- resources/views/modals/modal_confirmar_abrir_caja.blade.php -->
<div class="modal fade" id="modalConfirmacionAbrirCaja" tabindex="-1" aria-labelledby="modalConfirmarAbrirCajaLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalConfirmarAbrirCajaLabel">Abrir Caja</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>¿Abrir caja?</p>
                <p>Vendedor: {{ Auth::user()->name }}</p>
                <p>Fecha: {{ now()->toDateTimeString() }}</p>
                <p>Dinero inicial: 200 Bs</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                <button type="button" class="btn btn-primary" id="confirmarAbrirCaja" onclick="abrirCaja()">Sí</button>
            </div>
        </div>
    </div>
</div>
