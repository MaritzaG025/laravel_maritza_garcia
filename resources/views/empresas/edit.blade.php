@extends('layouts.app')

@section('titulo', 'Actualizar la Empresa')
@section('contenido')
    <h3 class="my-3">Actualizar la empresa: {{ $company->nombre_empresa ?? '' }} - {{ $company->clave_empresa ?? '' }}</h3>
    <div class="card">
        <div class="card-body">
            <form id="formularioEmpresasActualizar" method="POST"
                action="{{ route('empresas.update', $company->clave_empresa) }}">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="nombre_empresa" class="form-label">Nombre</label>
                            <input id="nombre_empresa" type="text" class="form-control" name="nombre_empresa"
                                value="{{ $company->nombre_empresa ?? '' }}">
                        </div>
                        <div class="mb-3">
                            <label for="tipo_identificacion" class="form-label">Tipo de identificación</label>
                            <select id="tipo_identificacion" class="form-select" name="tipo_identificacion">
                                <option value="" disabled {{ $company->tipo_identificacion ? '' : 'selected' }}>
                                    Selecciona un tipo</option>
                                <option value="CC" {{ $company->tipo_identificacion == 'CC' ? 'selected' : '' }}>Cédula
                                    de ciudadanía (CC)</option>
                                <option value="CE" {{ $company->tipo_identificacion == 'CE' ? 'selected' : '' }}>Cédula
                                    de extranjería (CE)</option>
                                <option value="NIT" {{ $company->tipo_identificacion == 'NIT' ? 'selected' : '' }}>NIT
                                </option>
                                <option value="RUT" {{ $company->tipo_identificacion == 'RUT' ? 'selected' : '' }}>RUT
                                </option>
                                <option value="pasaporte"
                                    {{ $company->tipo_identificacion == 'pasaporte' ? 'selected' : '' }}>Pasaporte</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="identificacion" class="form-label">Identificación</label>
                            <input id="identificacion" type="text" class="form-control" name="identificacion"
                                value="{{ $company->identificacion ?? '' }}"
                                pattern="^(?!NIT)[0-9]{7,15}$|^[0-9]{1,15}-[0-9]$"
                                title="Debe tener entre 7 y 15 números o formato NIT (123456789-0)">
                        </div>
                        <div class="mb-3">
                            <label for="razon_social" class="form-label">Razón social</label>
                            <input id="razon_social" type="text" class="form-control" name="razon_social"
                                value="{{ $company->razon_social ?? '' }}">
                        </div>
                        <div class="mb-3">
                            <label for="direccion" class="form-label">Dirección</label>
                            <input id="direccion" type="text" class="form-control" name="direccion"
                                value="{{ $company->direccion ?? '' }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input id="email" type="email" class="form-control" name="email"
                                value="{{ $company->email ?? '' }}">
                        </div>
                        <div class="mb-3">
                            <label for="telefono" class="form-label">Teléfono</label>
                            <input id="telefono" type="text" class="form-control" name="telefono"
                                value="{{ $company->telefono ?? '' }}" pattern="^\+?[0-9]{1,15}$"
                                title="Debe ser un número de teléfono válido con o sin código de país">
                        </div>
                        <div class="mb-3">
                            <label for="pagina_web" class="form-label">Página web</label>
                            <input id="pagina_web" type="text" class="form-control" name="pagina_web"
                                value="{{ $company->pagina_web ?? '' }}">
                        </div>
                        <div class="mb-3">
                            <label for="clave_empresa" class="form-label">Clave</label>
                            <input id="clave_empresa" type="text" class="form-control" name="clave_empresa"
                                value="{{ $company->clave_empresa ?? '' }}" pattern="[0-9]{6,}"
                                title="Debe tener al menos 6 dígitos" readonly style="opacity: 0.5; cursor: not-allowed;">
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-center mx-5 mt-3">
                    <button type="button" class="btn btn-outline-success me-3 w-100" data-bs-toggle="modal"
                        data-bs-target="#confirmationModal">Actualizar</button>
                    <a href="{{ route('empresas.show', $company->clave_empresa) }}"
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


    @if ($showErrorModal)
        <!-- Modal de Error -->
        <div class="modal fade" id="errorModal" tabindex="-1" aria-labelledby="errorModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="errorModalLabel">Lo sentimos!</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Empresa no encontrada.
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success" id="createButton">Crear</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

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
    @if ($showErrorModal)
        <script>
            $(document).ready(function() {
                $('#errorModal').modal('show');

                $('#createButton').click(function() {
                    window.location.href = "{{ route('empresas.create') }}";
                });

                $('#errorModal').on('hidden.bs.modal', function() {
                    window.location.href = "{{ route('empresas.index') }}";
                });
            });
        </script>
    @endif
    <script>
        $(document).ready(function() {
            $('#confirmarActualizacion').click(function() {
                $('#formularioEmpresasActualizar').submit();
            });
            $('#formularioEmpresasActualizar').submit(function(event) {
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
                                "{{ route('empresas.show', $company->clave_empresa) }}";
                            $('#mensajeTexto').text(response.successMessage);
                            $('#mensajeModal').modal('show');
                            $('#modalBtnOK').click(function() {
                                window.location.href = empresaShowUrl;
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
