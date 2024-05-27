@extends('layouts.app')

@section('titulo', 'Detalle de la Empresa')
@section('contenido')

    <h3 class="my-3">Detalle de la empresa: {{ $company->nombre_empresa ?? '' }} - {{ $company->clave_empresa ?? '' }}</h3>

    <div class="card">
        <div class="card-body">
            <p><strong>Clave:</strong> {{ $company->clave_empresa ?? '' }}</p>
            <p><strong>Nombre:</strong> {{ $company->nombre_empresa ?? '' }}</p>
            <p><strong>Razón Social:</strong> {{ $company->razon_social ?? '' }}</p>
            <p><strong>Tipo de identificación:</strong> {{ $company->tipo_identificacion ?? '' }}</p>
            <p><strong>Identificación:</strong> {{ $company->identificacion ?? '' }}</p>
            <p><strong>Email:</strong> {{ $company->email ?? '' }}</p>
            <p><strong>Teléfono:</strong> {{ $company->telefono ?? '' }}</p>
            <p><strong>Dirección:</strong> {{ $company->direccion ?? '' }}</p>
            <p><strong>Página Web:</strong> {{ $company->pagina_web ?? '' }}</p>
            <p><strong>Fecha de Creación:</strong> {{ $company->created_at ?? '' }}</p>
            <p><strong>Fecha de Actualización:</strong> {{ $company->updated_at ?? '' }}</p>
        </div>
        <div class="d-flex justify-content-center mx-5 my-3">
            <button class="btn btn-outline-success me-3 w-100"
                onclick="{{ $showErrorModal ? '$("#errorModal").modal("show");' : 'window.location.href=' . ($company ? "'" . route('empresas.edit', ['empresa' => $company->clave_empresa]) . "'" : "'#'") }}">Editar</button>
            <a id="delete-button"
                class="w-100 btn btn-outline-danger d-flex flex-column justify-content-center me-3 {{ !$company ? 'disabled' : '' }}"
                data-bs-toggle="modal" data-bs-target="#confirmDeleteModal">
                Eliminar
            </a>
            <form id="delete-form"
                action="{{ !$company && route('empresas.destroy', ['empresa' => $company->clave_empresa]) }}"
                method="POST" style="display: none;">
                @csrf
                @method('DELETE')
            </form>
            <a href="{{ route('empresas.index') }}"
                class="w-100 btn btn-outline-secondary d-flex flex-column justify-content-center me-3">Cancelar</a>
        </div>
    </div>

    <!-- Modal eliminación -->
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
    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmDeleteModalLabel">Confirmar Eliminación</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    ¿Estás seguro de que deseas eliminar esta empresa?
                </div>
                <div class="modal-footer">
                    <button id="confirmDeleteButton" type="button" class="btn btn-danger">Eliminar</button>
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

        <!-- Script para mostrar el modal -->
        <script>
            $(document).ready(function() {
                $('#errorModal').modal('show');

                $('#createButton').click(function() {
                    window.location.href = "{{ route('empresas.create') }}";
                });

                $('#errorModal').on('hidden.bs.modal', function() {
                    window.location.href = "{{ route('empresas.index') }}";
                });

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
                                .responseText); // Puedes mostrar el mensaje de error de otra manera
                            $('#mensajeModal').modal('show');
                            $('#modalBtn').click(function() {
                                $('#mensajeModal').modal('hide');
                            });
                        }
                    });
                });
            });
        </script>
    @endif
    <script>
        $('#confirmDeleteButton').click(function() {
            $('#delete-form').submit();
            $('#confirmDeleteModal').modal('hide');
        });

        $('#delete-form').submit(function(event) {
            // Evitar que el formulario se envíe normalmente
            event.preventDefault();

            // Obtener la URL del formulario
            var url = $(this).attr('action');

            // Enviar la solicitud AJAX
            $.ajax({
                type: 'DELETE',
                url: url,
                data: $(this).serialize(),
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
                    $('#mensajeTexto').text(
                        'Error al eliminar la empresa'); // Mensaje genérico de error
                    $('#mensajeModal').modal('show');
                    $('#modalBtn').click(function() {

                    });
                }
            });
        });
    </script>

@endsection
