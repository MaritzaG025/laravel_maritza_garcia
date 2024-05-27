@extends('layouts.app');

@section('titulo', 'Crear Empresa')
@section('contenido')
    <h3 class="my-3">Crear una nueva empresa</h3>
    <div class="card">
        <div class="card-body">
            <form id="formularioEmpresas" method="POST" action="{{ route('empresas.store') }}">
                {{-- seguridad --}}
                @csrf
                <div class="row">
                    {{-- Primer columna del formulario --}}
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="nombre_empresa" class="form-label">Nombre</label>
                            <input id="nombre_empresa" type="text" class="form-control" name="nombre_empresa" required>
                        </div>
                        <div class="mb-3">
                            <label for="tipo_identificacion" class="form-label">Tipo de identificación</label>
                            <select id="tipo_identificacion" class="form-select" name="tipo_identificacion" required>
                                <option value="" disabled selected>Selecciona un tipo</option>
                                <option value="CC">Cédula de ciudadanía (CC)</option>
                                <option value="CE">Cédula de extranjería (CE)</option>
                                <option value="NIT">NIT</option>
                                <option value="RUT">RUT</option>
                                <option value="pasaporte">Pasaporte</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="identificacion" class="form-label">Identificación</label>
                            <input id="identificacion" type="text" class="form-control" name="identificacion" required
                                pattern="^(?!NIT)[0-9]{7,15}$|^[0-9]{1,15}-[0-9]$"
                                title="Debe tener entre 7 y 15 números o formato NIT (123456789-0)">
                        </div>
                        <div class="mb-3">
                            <label for="razon_social" class="form-label">Razón social</label>
                            <input id="razon_social" type="text" class="form-control" name="razon_social" required>
                        </div>
                        <div class="mb-3">
                            <label for="direccion" class="form-label">Dirección</label>
                            <input id="direccion" type="text" class="form-control" name="direccion" required>
                        </div>
                    </div>
                    {{-- Segunda columna del formulario --}}
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input id="email" type="email" class="form-control" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="telefono" class="form-label">Teléfono</label>
                            <input id="telefono" type="text" class="form-control" name="telefono" required
                                pattern="^\+?[0-9]{1,15}$"
                                title="Debe ser un número de teléfono válido con o sin código de país">
                        </div>
                        <div class="mb-3">
                            <label for="pagina_web" class="form-label">Página web</label>
                            <input id="pagina_web" type="text" class="form-control" name="pagina_web" required>
                        </div>
                        <div class="mb-3">
                            <label for="clave_empresa" class="form-label">Clave</label>
                            <input id="clave_empresa" type="text" class="form-control" name="clave_empresa" required
                                pattern="[0-9]{6,}" title="Debe tener al menos 6 dígitos">
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-center mx-5 mt-3">
                    <button class="btn btn-outline-success me-3 w-100" id="submitBtn" type="submit">Crear</button>
                    <a href="{{ route('empresas.index') }}"
                        class="w-100 btn btn-outline-danger d-flex flex-column justify-content-center">Cancelar</a>
                </div>
            </form>
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
                    <button type="button" class="btn btn-success" id="modalBtn">OK</button>
                </div>
            </div>
        </div>
    </div>
    @include('partials.modal')
    {{-- @vite('resources/js/empresas/empresas.js') --}}
    <script>
        $(document).ready(function() {
            $('#formularioEmpresas').submit(function(event) {
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
                            var empresasIndexUrl = "{{ route('empresas.index') }}";
                            $('#mensajeTexto').text(response.successMessage);
                            $('#mensajeModal').modal('show');
                            $('#modalBtn').click(function() {
                                window.location.href = empresasIndexUrl;
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
