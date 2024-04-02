  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-collapse sidebar-light-navy  elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('home') }}" class="brand-link">
      <img src="{{ asset('img/univer_log.png')}}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="">
      <span class="brand-text font-weight-light">Univer</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
            <img src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-chat/ava3.webp" alt="User Image" class="img-circle elevation-2">
        </div>
        <div class="info">
          <a href="#" class="d-block">

            @php

                try {
                    $cadena = Auth::user()->name;
                    $array = explode(" ", $cadena);

                    if(isset($array[2]))
                    {
                        echo   ($array[2]) . ' ' . $array[0];
                    }else{
                        echo    $array[0] .  ' ' . $array[1];
                    }
                } catch (Exception  $th) {
                    echo 'Usuario Univer';
                }

            @endphp

            </a>
        </div>
      </div>

      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            @include('dashboard.colaboradores.template')

            @can('recursos humanos')
                @include('dashboard.recursosHumanos.template')
            @endcan

            @can('sistemas')
                @include('dashboard.sistemas.template')
            @endcan

            @include('dashboard.cerrarSesion.template')
        </ul>
      </nav>
    </div>
  </aside>
