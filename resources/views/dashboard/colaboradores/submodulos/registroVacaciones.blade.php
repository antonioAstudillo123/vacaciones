@extends('layouts.app')

@section('tituloPagina')
    Univer | Registro Vacaciones
@endsection

@section('contenido')

    @isset($bandera)

        @if($bandera)
        <main class="container" role="main">
            <div class="starter-template jumbotron">
                <h1 class="text-center display-5 text-danger">Actualización de Solicitud de Vacaciones</h1>
                <p class="lead text-justify">{{ $mensaje }}</p>
            </div>
        </main>

        @else
                <!-- Main content -->
            <section class="content p-3">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="sticky-top mb-3">
                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="card-title">Elige los días</h4>
                                    </div>
                                    <div class="card-body">
                                        <!-- the events -->
                                        <div id="external-events">
                                            <div class="external-event bg-success text-center">Vacaciones</div>
                                        </div>
                                    </div>
                                    <!-- /.card-body -->
                                </div>

                            </div>
                        </div>
                        <!-- /.col -->
                        <div class="col-md-8">
                            <div class="card card-primary">
                                <div class="card-body p-0">
                                    <!-- THE CALENDAR -->
                                    <div id="calendar"></div>
                                </div>
                                <!-- /.card-body -->
                            </div>
                            <!-- /.card -->
                        </div>
                        <!-- /.col -->
                    </div>
                <!-- /.row -->
      </section>

      <div class="container p-3">
          <div class="d-flex justify-content-center">
              <div class="">
                  <button id="btnSolicitar" class="btn btn-primary">Solicitar vacaciones</button>
                  <button id="btnSolicitarSpinner" class="btn btn-primary d-none" type="button" disabled>
                      <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                      <span class="visually-hidden">Procesando...</span>
                    </button>

              </div>
          </div>
      </div>
      <!-- /.content -->
        @endif
    @endisset

@endsection

@section('scriptsPagina')
   <!-- fullCalendar 2.2.5 -->
   <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>
   <script src="{{ asset('js/moments/moment.min.js') }}"></script>
   <script src="{{ asset('js/fullcalendar/locales/es.js') }}"></script>
   <script src="{{ asset('js/fullcalendar/locales-all.js') }}"></script>

   @if(!$bandera)
    <script type="module" src="{{ asset('js/pages/registroVacaciones/app.js') }}"></script>
   @endif

@endsection
