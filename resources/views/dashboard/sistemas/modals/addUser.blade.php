<!-- Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Información del usuario</h5>

        </div>
        <div class="modal-body">
            <form action="" id="formAddUser">
                <div class="row mb-3">
                    <label for="userNameAdd" class="col-sm-2 col-form-label">Usuario</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="userNameAdd" placeholder="Ingresa el username">
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="emailUserAdd" class="col-sm-2 col-form-label">Email</label>
                        <div class="col-sm-10">
                        <input type="email" class="form-control" id="emailUserAdd" placeholder="Ingresa el email">
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="passwordAdd" class="col-sm-2 col-form-label">Password</label>
                    <div class="col-sm-10">
                        <input type="password" class="form-control" id="passwordAdd" value="" placeholder="********">
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="colaboradorUserAdd" class="col-sm-2 col-form-label">Colaboradores</label>
                    <div class="col-sm-10">
                        <select id="colaboradorUserAdd" class="form-control form-select-lg mb-3" aria-label=".form-select-lg example">
                            <option value="" selected disabled>Elige un colaborador</option>
                            @foreach ($colaboradores as $colaborador )
                                <option value="{{ $colaborador->numeroEmpleado }}">{{ $colaborador->numeroEmpleado }} - {{ $colaborador->colaborador }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="roleUserAddSelect" class="col-sm-2 col-form-label">Perfiles</label>
                    <div class="col-sm-10">
                        <select id="roleUserAddSelect" class="form-control form-select-lg mb-3" aria-label=".form-select-lg example">
                            <option value="" selected disabled>Elige un perfil</option>
                            @foreach ($roles as $role )
                                <option value="{{ $role->name }}">{{ ucfirst($role->name) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
          <button type="button" id="btnCerrarAddUser"  class="btn btn-secondary" data-bs-dismiss="modalAprobarSolicitud">Cerrar</button>
          <button type="button" value="" id="btnSaveUser" class="btn btn-primary">Guardar cambios</button>
        </div>
      </div>
    </div>
  </div>
