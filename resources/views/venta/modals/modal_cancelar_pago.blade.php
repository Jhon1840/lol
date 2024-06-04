 <div class="modal fade" id="modalCancelar" tabindex="-1" aria-labelledby="modalPagoLabel" aria-hidden="true">
     <div class="modal-dialog">
         <div class="modal-content">
             <div class="modal-header">
                 <h5 class="modal-title" id="modalPagoLabel">Cancelar Venta</h5>
                 <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
             </div>
             <div class="modal-body">
                 <form method="POST" action="{{ route('cancelar-venta') }}" id="pagoForm">
                     @csrf
                     <input type="hidden" name="estado" value="Cancelado">
                     <input type="hidden" name="order_id" value="{{ uniqid() }}">
                     <!-- Enviar fecha del servidor -->
                     <input type="hidden" name="fecha_servidor" value="{{ now()->toDateTimeString() }}">
                     <div id="productosForm"></div>
                     <div class="d-flex justify-content-between mt-3">
                         <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                         <button type="submit" class="btn btn-primary">Sí</button>
                     </div>
                 </form>
             </div>
         </div>
     </div>
 </div>
