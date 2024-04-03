
@extends('layouts.app')



@section('contenido')
<div class="login-box content mx-auto">
    <div class="login-logo">
      <a href="{{ route('home') }}"><b>Univer</b>{{ date('Y') }}</a>
    </div>
    <!-- /.login-logo -->
    <div class="card">
        <div class="card-body login-card-body">
            <p class="login-box-msg">Asegúrese de que su nueva contraseña sea segura y contenga al menos 8 caracteres, incluyendo letras mayúsculas, minúsculas, números y caracteres especiales.</p>

            <form id="formUpdatePassword" method="post">
                <div class="input-group mb-3">
                    <input id="password1" type="password" class="form-control" placeholder="Password">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <input id="password2" type="password" class="form-control" placeholder="Confirmar Password">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <button id="updatePassword" type="button" class="btn btn-primary btn-block">Cambiar password</button>
                    </div>
                    <!-- /.col -->
                </div>
        </form>
      </div>
      <!-- /.login-card-body -->
    </div>
</div>
  <!-- /.login-box -->
@endsection


@section('scriptsPagina')
<script src="{{ asset('js/pages/gestionarUsuarios/passwordReset.js') }}" type="module"></script>

@endsection

