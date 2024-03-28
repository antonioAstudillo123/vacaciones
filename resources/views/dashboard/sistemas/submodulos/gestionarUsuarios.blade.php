@extends('layouts.app')

@section('tituloPagina')
    Univer | Gestión de usuarios
@endsection

@section('contenido')

<section class="content p-3">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <div class="d-flex justify-content-between">
                <div class="">
                    <h3 class="card-title">Listado de usuarios</h3>
                </div>
                <div class="">
                    <button type="button" id="btnAddUser" class="btn btn-success btn-sm">Agregar usuario</button>
                </div>
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
              <table id="tablaUsuarios" class="table table-bordered table-hover">
                  <thead>
                      <tr>
                          <th>Id</th>
                          <th>Nombre</th>
                          <th>Email</th>
                          <th>Puesto</th>
                          <th>Opciones</th>
                      </tr>
                  </thead>
                  <tbody class="text-center">
                  </tbody>
              </table>
          </div>
          <!-- /.card-body -->
        </div>
        <!-- /.card -->
      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->
  <!-- /.container-fluid -->
</section>

@include('dashboard.sistemas.modals.editUser')
@include('dashboard.sistemas.modals.confirmDelete')
@include('dashboard.sistemas.modals.addUser')

@endsection

@section('scriptsPagina')
<script src="{{ asset('js/jquery/jquery-3.7.1.js') }}"></script>
<script src="{{ asset('js/datatable/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('js/datatable/dataTables.js') }}"></script>
<script src="{{ asset('js/datatable/dataTables.bootstrap5.js') }}"></script>

<script type="module" src="{{ asset('js/pages/gestionarUsuarios/app.js') }}"></script>

@endsection
