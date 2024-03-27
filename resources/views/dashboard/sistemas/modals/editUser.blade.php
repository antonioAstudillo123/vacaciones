<!-- Modal -->
<div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Información del usuario</h5>

        </div>
        <div class="modal-body">
            <form action="" id="formEditUser">
                <div class="row mb-3">
                    <label for="userName" class="col-sm-2 col-form-label">Usuario</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="userName">
                        <input type="hidden" name="" id="idUser">
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="emailUser" class="col-sm-2 col-form-label">Email</label>
                        <div class="col-sm-10">
                        <input type="email" class="form-control" id="emailUser">
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="passwordUser" class="col-sm-2 col-form-label">Password</label>
                        <div class="col-sm-10">
                        <input type="password" class="form-control" id="passwordUser" value="" placeholder="********">
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
          <button type="button" id="btnCerrarModalSolicitud"  class="btn btn-secondary" data-bs-dismiss="modalAprobarSolicitud">Cerrar</button>
          <button type="button" value="" id="btnEditUser" class="btn btn-primary">Guardar cambios</button>
        </div>
      </div>
    </div>
  </div>
