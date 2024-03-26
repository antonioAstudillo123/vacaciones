@extends('layouts.app')

@section('tituloPagina')
    Univer | Resumen solicitudes
@endsection

@section('contenido')

<section class="content p-3">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <div class="d-flex justify-content-between container">
                <div class="">
                    <h3 class="card-title">Resumen de solicitudes {{ date('Y') }}</h3>
                </div>
                <div class="">
                    <div class="btn-group dropleft">
                        <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                          Reportes
                        </button>
                        <div class="dropdown-menu">
                            <a id="btnExcelReport" class="dropdown-item" href="#">Excel</a>
                            <a id="btnPdfReport" class="dropdown-item" href="#">PDF</a>
                        </div>
                      </div>
                </div>
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
              <table id="tablaHistorico" class="table table-bordered table-hover">
                  <thead>
                      <tr>
                          <th>Colaborador</th>
                          <th>Número empleado</th>
                          <th>Fecha ingreso</th>
                          <th>Días aprobados</th>
                          <th>Días restantes</th>
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

@endsection

@section('scriptsPagina')
<script src="{{ asset('js/datatable/dataTables.js') }}"></script>
<script src="{{ asset('js/datatable/dataTables.bootstrap5.js') }}"></script>
<script src="{{ asset('js/datatable/dataTables.responsive.js') }}"></script>
<script src="{{ asset('js/datatable/responsive.bootstrap5.js') }}"></script>
 {{-- CDN NECESARIO PARA GENERAR EL ARCHIVO EXCEL --}}
 <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.core.min.js" integrity="sha512-UhlYw//T419BPq/emC5xSZzkjjreRfN3426517rfsg/XIEC02ggQBb680V0VvP+zaDZ78zqse3rqnnI5EJ6rxA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

 {{-- CDN NECESARIOS PARA GENERAR PDF --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.5.3/jspdf.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.6/jspdf.plugin.autotable.min.js"></script>
<script type="module" src="{{ asset('js/pages/historico/app.js') }}"></script>

@endsection
