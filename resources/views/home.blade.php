@extends('layouts.app')

@section('tituloPagina' , 'Univer')

@section('contenido')


<div class="container mt-4">


    @isset($role)

        @if($role == 'colaborador')
            <div class="starter-template jumbotron">
                <h1 class="text-center display-5 text-dark">¡Bienvenido al sistema de gestión de Vacaciones!</h1>
                <p class="lead text-justify">Estimado <span class="text-primary font-weight-bold">{{ Auth::user()->name }}</span></p>
                <p class="lead text-justify">Nos complace darte la bienvenida al Sistema de gestión de vacaciones. Aqui podrás solicitar tus vacaciones de manera rápida y sencilla.</p>
            </div>
        @else
        <div class="starter-template jumbotron">
            <h1 class="text-center display-5 text-dark">¡Bienvenido al sistema de gestión de Vacaciones!</h1>
            <p class="lead text-justify">Estimado <span class="text-primary font-weight-bold">{{ Auth::user()->name }}</span></p>
            <p class="lead text-justify">Te damos la bienvenida al Sistema de Gestión de Vacaciones. Este sistema te brinda herramientas para consultar de manera eficiente la información relacionada con las vacaciones de los empleados.</p>
        </div>
        @endif
    @endisset


    <section class="content">
        <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <div class="col-lg-6 col-6">
            <!-- small box -->
            <div class="small-box bg-info">
                <div class="inner">
                <h3>{{ $diasDisponibles }}</h3>

                <p>Días Disponibles</p>
                </div>
                <div class="icon">
                <i class="ion ion-bag"></i>
                </div>
            </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-6 col-6">
            <!-- small box -->
            <div class="small-box bg-success">
                <div class="inner">
                <h3>{{ $diasUtilizados }}</h3>

                <p>Días Utilizados</p>
                </div>
                <div class="icon">
                <i class="ion ion-stats-bars"></i>
                </div>
            </div>
            </div>
            <!-- ./col -->
        </div>
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->


</div>
@endsection
