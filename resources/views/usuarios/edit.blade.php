@extends('layouts.app')

@section('titulo', 'Actualizar Usuario')

@section('contenido')
    <h3 class="my-3">Actualizar Usuario: {{ $usuario->nombre ?? '' }} - {{ $empresa->nombre_empresa ?? '' }} ({{ $empresa->clave_empresa ?? '' }})</h3>
    <div class="card">
        <div class="card-body">
            <form id="formularioUsuariosActualizar" method="POST" action="{{ route('usuarios.update', $usuario->id_usuario) }}">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre</label>
                            <input id="nombre" type="text" class="form-control" name="nombre"
                                value="{{ $usuario->nombre ?? '' }}">
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input id="email" type="email" class="form-control" name="email"
                                value="{{ $usuario->email ?? '' }}">
                        </div>
                        <div class="mb-3">
                            <label for="tipo_usuario" class="form-label">Tipo de Usuario</label>
                            <select id="tipo_usuario" class="form-select" name="tipo_usuario">
                                <option value="" disabled>Selecciona un tipo de usuario</option>
                                <option value="administrador" {{ $usuario->tipo_usuario == 'administrador' ? 'selected' : '' }}>Administrador</option>
                                <option value="tecnico" {{ $usuario->tipo_usuario == 'tecnico' ? 'selected' : '' }}>Técnico</option>
                                <option value="desarrollador" {{ $usuario->tipo_usuario == 'desarrollador' ? 'selected' : '' }}>Desarrollador</option>
                                <option value="lector" {{ $usuario->tipo_usuario == 'lector' ? 'selected' : '' }}>Lector</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="usuario" class="form-label">Usuario</label>
                            <input id="usuario" type="text" class="form-control" name="usuario"
                                value="{{ $usuario->usuario ?? '' }}">
                        </div>
                        <div class="mb-3">
                            <label for="contrasen_a" class="form-label">Contraseña</label>
                            <input id="contrasen_a" type="text" class="form-control" name="contrasen_a" value="{{ $usuario->contrasen_a ?? '' }}">
                        </div>
                        <div class="mb-3">
                            <label for="activo" class="form-label">Activo</label>
                            <select id="activo" class="form-select" name="activo">
                                <option value="1" {{ $usuario->activo == 1 ? 'selected' : '' }}>Sí</option>
                                <option value="0" {{ $usuario->activo == 0 ? 'selected' : '' }}>No</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-center mx-5 mt-3">
                    <button type="button" class="btn btn-outline-success me-3 w-100" data-bs-toggle="modal"
                        data-bs-target="#confirmationModal">Actualizar</button>
                        <a href="{{ route('usuarios.show', ['empresaId' => $empresa->id_empresas, 'usuarioId' => $usuario->id_usuario]) }}"
                            class="w-100 btn btn-outline-secondary d-flex flex-column justify-content-center">Cancelar</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal de confirmación -->
    <div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmationModalLabel">Confirmación</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    ¿Estás seguro de que deseas realizar la actualización?
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success" id="confirmarActualizacion">Confirmo
                        actualización</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="mensajeModal" tabindex="-1" aria-labelledby="mensajeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="mensajeModalLabel">Mensaje</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p id="mensajeTexto"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" id="modalBtnOK">OK</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $('#confirmarActualizacion').click(function() {
                $('#formularioUsuariosActualizar').submit();
            });
            $('#formularioUsuariosActualizar').submit(function(event) {
                // Evitar que el formulario se envíe normalmente
                event.preventDefault();

                // Obtener la URL del formulario
                var url = $(this).attr('action');

                // Obtener los datos del formulario
                var formData = $(this).serialize();

                // Enviar la solicitud AJAX
                $.ajax({
                    type: 'POST',
                    url: url,
                    data: formData,
                    success: function(response) {
                        if (response && response.successMessage) {
                            var empresaShowUrl =
                                "{{ route('usuarios.show', ['empresaId' => $empresa->id_empresas, 'usuarioId' => $usuario->id_usuario]) }}";
                            $('#mensajeTexto').text(response.successMessage);
                            $('#mensajeModal').modal('show');
                            $('#modalBtnOK').click(function() {
                                window.location.href = empresaShowUrl;
                            });
                        }

                        if (response && response.errorMessage) {
                            $('#mensajeTexto').text(response.errorMessage);
                            $('#mensajeModal').modal('show');
                            $('#modalBtnOK').click(function() {
                                $('#mensajeModal').modal('hide');
                                $('#confirmationModal').modal('hide');
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                        $('#mensajeTexto').text(xhr
                            .responseText);
                        $('#mensajeModal').modal('show');
                        $('#modalBtnOK').click(function() {
                            $('#mensajeModal').modal('hide');
                            $('#confirmationModal').modal('hide');
                        });
                    }
                });
            });
        });
    </script>
@endsection
