@extends('layouts.app')

@section('titulo', 'Crear Usuario')

@section('contenido')
    <div class="container">
        <h3 class="my-3">Crear un nuevo usuario</h3>
        <div class="card">
            <div class="card-body">
                <form id="formularioUsuarios" method="POST" action="{{ route('usuarios.store') }}">
                    {{-- Seguridad --}}
                    @csrf
                    <input type="hidden" name="empresa_id" value="{{ $empresaId }}">
                    <div class="row">
                        {{-- Primera columna del formulario --}}
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nombre" class="form-label">Nombre</label>
                                <input id="nombre" type="text" class="form-control" name="nombre" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input id="email" type="email" class="form-control" name="email" required>
                            </div>
                            <div class="mb-3">
                                <label for="tipo_usuario" class="form-label">Tipo de Usuario</label>
                                <select id="tipo_usuario" class="form-select" name="tipo_usuario" required>
                                    <option value="" disabled selected>Selecciona un tipo de usuario</option>
                                    <option value="administrador">Administrador</option>
                                    <option value="tecnico">Técnico</option>
                                    <option value="desarrollador">Desarrollador</option>
                                    <option value="lector">Lector</option>
                                </select>
                            </div>
                        </div>
                        {{-- Segunda columna del formulario --}}
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="usuario" class="form-label">Usuario</label>
                                <input id="usuario" type="text" class="form-control" name="usuario" required>
                            </div>
                            <div class="mb-3">
                                <label for="contrasena" class="form-label">Contraseña</label>
                                <input id="contrasena" type="password" class="form-control" name="contrasena" required>
                            </div>
                            <div class="mb-3">
                                <label for="activo" class="form-label">Activo</label>
                                <select id="activo" class="form-select" name="activo" required>
                                    <option value="" disabled selected>Selecciona una opción</option>
                                    <option value="1">Sí</option>
                                    <option value="0">No</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-center mx-5 mt-3">
                        <button class="btn btn-outline-success me-3 w-100" id="submitBtn" type="submit">Crear</button>
                        <a href="{{ route('usuarios.index') }}"
                            class="w-100 btn btn-outline-danger d-flex flex-column justify-content-center">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="mensajeModal" tabindex="-1" aria-labelledby="mensajeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="mensajeModalLabel"><strong>Mensaje</strong></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p id="mensajeTexto"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" id="modalBtn">OK</button>
                </div>
            </div>
        </div>
    </div>

    {{-- @vite('resources/js/usuarios/create.js') --}}
    <script>
        $(document).ready(function() {
            $('#formularioUsuarios').submit(function(event) {
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
                            $('#mensajeTexto').text(response.successMessage);
                            $('#mensajeModal').modal('show');
                            $('#modalBtn').click(function() {
                                window.location.href = "{{ route('usuarios.index') }}";
                            });
                        }

                        if (response && response.errorMessage) {
                            $('#mensajeTexto').text(response.errorMessage);
                            $('#mensajeModal').modal('show');
                            $('#modalBtn').click(function() {
                                $('#mensajeModal').modal('hide');
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                        $('#mensajeTexto').text(xhr
                            .responseText);
                        $('#mensajeModal').modal('show');
                        $('#modalBtn').click(function() {
                            $('#mensajeModal').modal('hide');
                        });
                    }
                });
            });
        });
    </script>
@endsection
