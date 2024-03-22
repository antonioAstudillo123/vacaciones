<li class="nav-item">
    <a href="{{ route('logout') }}" class="nav-link"
    onclick="event.preventDefault();
    document.getElementById('logout-form').submit();">
      <i class="nav-icon fas fa-power-off" style="--fa-primary-color: #f70820; --fa-primary-opacity: 2; --fa-secondary-color: #f2072b;"></i>
      <p class="text-danger">
        Cerrar Sesión
      </p>
    </a>


    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
        @csrf
    </form>
</li>
