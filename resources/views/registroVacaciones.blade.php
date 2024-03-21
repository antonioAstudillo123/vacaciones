@extends('layouts.app')

@section('tituloPagina')
    Univer | Registro Vacaciones
@endsection

@section('contenido')
    <!-- Main content -->
    <section class="content mt-5">
      <div class="container p-5">
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
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
@endsection

@section('scriptsPagina')
   <!-- fullCalendar 2.2.5 -->
   <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>
   <script src="{{ asset('js/moments/moment.min.js') }}"></script>
   <script src="{{ asset('js/fullcalendar/locales/es.js') }}"></script>
   <script src="{{ asset('js/fullcalendar/locales-all.js') }}"></script>
   <script src="{{ asset('js/pages/registroVacaciones/app.js') }}"></script>

@endsection
