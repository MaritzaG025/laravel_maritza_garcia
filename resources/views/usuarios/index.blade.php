@extends('layouts.app')

@section('titulo', 'Listado de usuarios')

@section('contenido')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h3 class="my-3">Seleccionar Empresa</h3>
                <form id="usuariosForm">
                    @csrf
                    <div class="mb-3">
                        <label for="empresa" class="form-label">Empresa</label>
                        <select class="form-select" id="empresa" name="empresa" required>
                            <option value="" disabled selected>Selecciona una empresa</option>
                            @foreach ($empresas as $empresa)
                                <option value="{{ $empresa->id_empresas }}">{{ $empresa->nombre_empresa }} -
                                    {{ $empresa->clave_empresa }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn_verde">Ver Usuarios</button>
                    <!-- Botón para crear un nuevo usuario -->
                    <button type="button" id="crearUsuarioBtn" class="btn btn_verde">Crear Nuevo Usuario</button>
                </form>
            </div>
        </div>
        <div class="row justify-content-center mt-4">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title" style="color: #00BD56"><strong>Usuarios</strong></h5>
                        <div id="usuariosTable" style="display: none;" class="table-responsive">
                            <table class="table table-striped">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>Id</th>
                                        <th>Nombre</th>
                                        <th>Email</th>
                                        <th>Usuario</th>
                                        <th>Contraseña</th>
                                        <th>Tipo de Usuario</th>
                                        <th>Activo</th>
                                    </tr>
                                </thead>
                                <tbody id="usuariosBody"></tbody>
                            </table>
                        </div>
                        <div id="noUsuariosMsg" style="display: none;">
                            <p id="noUsuariosText" style="display: none;">No se encontraron usuarios para la empresa <span
                                    id="empresaName"></span>.</p>
                            <p id="noUsuariosDefault">No se encontraron usuarios para la empresa seleccionada.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('partials.modal')

    <script>
        $(document).ready(function() {
            $(document).ready(function() {
                // Función para obtener los parámetros de la URL
                function getParameterByName(name, url) {
                    if (!url) url = window.location.href;
                    name = name.replace(/[\[\]]/g, '\\$&');
                    var regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)'),
                        results = regex.exec(url);
                    if (!results) return null;
                    if (!results[2]) return '';
                    return decodeURIComponent(results[2].replace(/\+/g, ' '));
                }

                var errorMessage = getParameterByName('error_message');
                console.log(errorMessage);
                if (errorMessage) {
                    $('#errorModal .modal-body p').text(errorMessage);
                    $('#errorModal').modal('show');
                }
            });

            // Función para validar la selección de empresa antes de crear un nuevo usuario
            $('#crearUsuarioBtn').click(function() {
                var empresaId = $('#empresa').val();
                if (!empresaId) {
                    alert('Debes seleccionar una empresa antes de crear un nuevo usuario.');
                    return;
                }
                window.location.href = "{{ route('usuarios.create') }}?empresa_id=" + empresaId;
            });

            $('#usuariosForm').submit(function(event) {
                event.preventDefault();
                var empresaId = $('#empresa').val();
                var empresaNombre = $('#empresa option:selected').text();
                $.ajax({
                    type: 'GET',
                    url: '{{ route('usuarios.empresa') }}',
                    data: {
                        empresa_id: empresaId
                    },
                    success: function(response) {
                        if (response.usuarios && response.usuarios.length > 0) {
                            $('#usuariosBody').empty();
                            response.usuarios.forEach(function(usuario) {
                                let userActive = 'Sí';
                                if (usuario.activo == 0) {
                                    userActive = 'No';
                                }
                                $('#usuariosBody').append('<tr><td>' + usuario
                                    .id_usuario + '</td><td>' + usuario.nombre +
                                    '</td><td>' + usuario.email + '</td><td>' +
                                    usuario.usuario + '</td><td>' + usuario
                                    .contrasen_a + '</td><td>' + usuario
                                    .tipo_usuario + '</td><td>' + userActive +
                                    '</td></tr>');
                            });
                            $('#usuariosTable').show();
                            $('#noUsuariosMsg').hide();
                        } else {
                            $('#usuariosTable').hide();
                            $('#noUsuariosText').html(
                                'No se encontraron usuarios para la empresa <span id="empresaName">' +
                                empresaNombre + '</span>.').show();
                            $('#noUsuariosDefault').hide();
                            $('#noUsuariosMsg').show();
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                    }
                });
            });
        });
    </script>
@endsection
