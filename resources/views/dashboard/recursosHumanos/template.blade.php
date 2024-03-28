<li class="nav-item">
    <a href="#" class="nav-link">
      <i class="nav-icon fas fa-balance-scale-right"></i>
      <p>
        Recursos humanos
        <i class="right fas fa-angle-left"></i>
      </p>
    </a>
    <ul class="nav nav-treeview">
        @can('solicitudes')
            <li class="nav-item">
                <a href="{{ route('rh.index') }}" class="nav-link">
                <i class="far fa-circle nav-icon text-primary"></i>
                <p>Solicitudes {{ date('Y') }}</p>
                </a>
            </li>
        @endcan
        @can('empleados')
            <li class="nav-item">
                <a href="../index2.html" class="nav-link">
                    <i class="far fa-circle nav-icon text-primary"></i>
                    <p>Empleados</p>
                </a>
            </li>
        @endcan
    </ul>
</li>
