<li class="nav-item">
    <a href="#" class="nav-link">
      {{-- <i class="nav-icon fas fa-tachometer-alt"></i> --}}
      <i class="nav-icon  fas fa-users"></i>
      <p>
        Colaboradores
        <i class="right fas fa-angle-left"></i>
      </p>
    </a>
    <ul class="nav nav-treeview">
      <li class="nav-item">
        <a href="{{ route('registroVacaciones.index') }}" class="nav-link">
          <i class="far fa-circle nav-icon text-primary"></i>
          <p>Solicitar vacaciones</p>
        </a>
      </li>
      <li class="nav-item">
        <a href="{{ route('gestionarSolicitudes.index') }}" class="nav-link">
          <i class="far fa-circle nav-icon text-primary"></i>
          <p>Gestionar solicitudes</p>
        </a>
      </li>
    </ul>
</li>
