  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-collapse sidebar-light-navy  elevation-4">
    <!-- Brand Logo -->
    <a href="../index3.html" class="brand-link">
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
          <a href="#" class="d-block">Antonio Astudillo</a>
        </div>
      </div>

      <!-- SidebarSearch Form -->
      <div class="form-inline">
        <div class="input-group" data-widget="sidebar-search">
          <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
          <div class="input-group-append">
            <button class="btn btn-sidebar">
              <i class="fas fa-search fa-fw"></i>
            </button>
          </div>
        </div>
      </div>

      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            @include('dashboard.colaboradores.template')
            @include('dashboard.recursosHumanos.template')
            @include('dashboard.sistemas.template')
            @include('dashboard.cerrarSesion.template')
        </ul>
      </nav>
    </div>
  </aside>
