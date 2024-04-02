<!-- Modal -->
<div class="modal fade" id="permisoUserModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Información del usuario</h5>

        </div>
        <div class="modal-body">
            <form action="" id="formAddUserPermisos">
                <div class="row mb-3">
                    <label for="nameUserPermisos" class="col-sm-2 col-form-label">Nombre</label>
                    <div class="col-sm-10">
                        <input readonly type="text" class="form-control" id="nameUserPermisos" placeholder="Ingresa el username">

                    </div>
                </div>
                <div class="row mb-3">
                    <label for="emailUserPermisos" class="col-sm-2 col-form-label">Email</label>
                        <div class="col-sm-10">
                        <input readonly type="email" class="form-control" id="emailUserPermisos" placeholder="Ingresa el email">
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="perfilesUserPermisos" class="col-sm-2 col-form-label">Perfiles</label>
                    <div class="col-sm-10">
                        <select id="perfilesUserPermisos" class="form-control form-select-lg mb-3" aria-label=".form-select-lg example">
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
          <button type="button"  class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
          <button type="button" value="" id="btnAddRolePermiso" class="btn btn-primary">Guardar cambios</button>
        </div>
      </div>
    </div>
  </div>
