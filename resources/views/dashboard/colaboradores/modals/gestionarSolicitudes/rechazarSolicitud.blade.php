<!-- Modal -->
<div class="modal fade" id="modalRechazarSolicitud" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Detalles del rechazo</h5>

        </div>
        <div class="modal-body">
            <form id="formRechazoSolicitud">
                <div class="mb-3">
                    <label for="colaboradorInputModal" class="form-label">Colaborador</label>
                    <input type="text" readonly class="form-control" id="colaboradorInputModal" placeholder="N/A">
                    <input type="hidden" id="idSolicitudRechazo">
                </div>
                <div class="mb-3">
                    <label for="motivoRechazoText" class="form-label">Motivo del rechazo</label>
                    <textarea class="form-control" placeholder="Razón por la que está rechazando la solicitud" id="motivoRechazoText" rows="3"></textarea>
                </div>
            </form>
        </div>
        <div class="modal-footer">
          <button type="button" id="btnCerrarModalRechazo"  class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
          <button type="button" value="" id="btnRechazoSolicitud" class="btn btn-primary">Continuar</button>
        </div>
      </div>
    </div>
  </div>
