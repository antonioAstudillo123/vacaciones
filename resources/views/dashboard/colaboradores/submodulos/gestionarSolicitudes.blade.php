@extends('layouts.app')

@section('tituloPagina')
    Univer | Gestionar solicitudes
@endsection

@section('contenido')

<section class="content p-3">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Solicitudes de vacaciones</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <table id="tablaSolicitudes" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Colaborador</th>
                            <th>Fecha solicitud</th>
                            <th>Días</th>
                            <th>Estado</th>
                            <th>Opciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>Hector Leon</td>
                            <td>24-03-2024</td>
                            <td>4</td>
                            <td>Pendiente</td>
                        </tr>
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

  {{-- BLOQUE DE MODALS --}}
  @include('dashboard.colaboradores.modals.gestionarSolicitudes.showFechas')

@endsection

@section('scriptsPagina')
<script src="{{ asset('js/datatable/dataTables.js') }}"></script>
<script src="{{ asset('js/datatable/dataTables.bootstrap5.js') }}"></script>
<script src="{{ asset('js/datatable/dataTables.responsive.js') }}"></script>
<script src="{{ asset('js/datatable/responsive.bootstrap5.js') }}"></script>
<script type="module" src="{{ asset('js/pages/gestionarSolicitudes/app.js') }}"></script>
@endsection
