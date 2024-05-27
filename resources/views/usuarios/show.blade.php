@extends('layouts.app')

@section('titulo', 'Detalle de la Empresa y Usuario')
@section('contenido')

    <h3 class="my-3">Detalle del usuario: {{ $usuario->nombre ?? '' }}</h3>

    <div class="card">
        <div class="card-body">
            <p><strong>Nombre:</strong> {{ $usuario->nombre ?? '' }}</p>
            <p><strong>Email:</strong> {{ $usuario->email ?? '' }}</p>
            <p><strong>Usuario:</strong> {{ $usuario->usuario ?? '' }}</p>
            <p><strong>Contraseña:</strong> {{ $usuario->contrasen_a ?? '' }}</p>
            <p><strong>Tipo de usuario:</strong> {{ $usuario->tipo_usuario ?? '' }}</p>
            <p><strong>Empresa:</strong> {{ $company->nombre_empresa ?? '' }} - {{ $company->clave_empresa ?? '' }}</p>
            <p><strong>Activo:</strong> {{ $usuario->activo ? 'Sí' : 'No' }}</p>
            <p><strong>Fecha de Creación:</strong> {{ $usuario->created_at ?? '' }}</p>
            <p><strong>Fecha de Actualización:</strong> {{ $usuario->updated_at ?? '' }}</p>
        </div>
    </div>

    <div class="d-flex justify-content-center mx-5 my-3">
        <button class="btn btn-outline-success me-3 w-100"
            onclick="{{ $showErrorModal ? '$("#errorModal").modal("show");' : ($company ? 'window.location.href="' . route('usuarios.edit', ['empresa_id' => $company->id_empresas, 'usuario_id' => $usuario->id_usuario]) . '";' : "'#'") }}">Editar</button>
        <a id="delete-button"
            class="w-100 btn btn-outline-danger d-flex flex-column justify-content-center me-3 {{ !$company ? 'disabled' : '' }}"
            data-bs-toggle="modal" data-bs-target="#confirmDeleteModal">
            Eliminar
        </a>
        <form id="delete-form" action="{{$company ?? route('usuarios.destroy', ['id_usuario' => $usuario->id_usuario]) }}"
            method="POST" style="display: none;">
            @csrf
            @method('DELETE')
        </form>


        <a href="{{ route('usuarios.index') }}" class="btn btn-outline-secondary w-100">Cancelar</a>
    </div>

    <!-- Modal de confirmación de eliminación -->
    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmDeleteModalLabel">Confirmar eliminación</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    ¿Está seguro de que desea eliminar este usuario?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" id="confirmDeleteButton">Eliminar</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                </div>
            </div>
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
                        {{ $errorMessage }}
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
                    if ("{{ $errorMessage }}" === 'No se encontró la empresa.') {
                        window.location.href = "{{ route('empresas.create') }}";
                    } else {
                        window.location.href =
                            "{{ route('usuarios.create') }}?empresa_id={{ $company->id_empresas }}";
                    }
                });

                $('#errorModal').on('hidden.bs.modal', function() {
                    window.location.href = "{{ route('usuarios.create') }}";
                });

            });
        </script>
    @endif
    <script>
        $(document).ready(function() {
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
                            var usuariosIndexUrl = "{{ route('usuarios.index') }}";
                            $('#mensajeTexto').text(response.successMessage);
                            $('#mensajeModal').modal('show');
                            $('#modalBtn').click(function() {
                                window.location.href = usuariosIndexUrl;
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
                            'Error al eliminar el usuario'); // Mensaje genérico de error
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
