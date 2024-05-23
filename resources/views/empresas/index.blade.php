@extends('layouts.app')

@section('titulo', 'Listado de Empresa')
@section('contenido')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Listado de Empresas</h2>
            <a href="{{ route('empresas.create') }}" class="btn btn_verde">Crear Empresa</a>
        </div>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead class="thead-dark">
                    <tr>
                        <th>Id</th>
                        <th>Clave</th>
                        <th>Tipo de Identificación</th>
                        <th>Identificación</th>
                        <th>Nombre</th>
                        <th>Razón Social</th>
                        <th>Email</th>
                        <th>Teléfono</th>
                        <th>Dirección</th>
                        <th>Página Web</th>
                        <th>Fecha de Actualización</th>
                        <th>Fecha de Creación</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($empresas as $empresa)
                        <tr>
                            <td>{{ $empresa->id_empresas }}</td>
                            <td>{{ $empresa->clave_empresa }}</td>
                            <td>{{ $empresa->tipo_identificacion }}</td>
                            <td>{{ $empresa->identificacion }}</td>
                            <td>{{ $empresa->nombre }}</td>
                            <td>{{ $empresa->razon_social }}</td>
                            <td>{{ $empresa->email }}</td>
                            <td>{{ $empresa->telefono }}</td>
                            <td>{{ $empresa->direccion }}</td>
                            <td>{{ $empresa->pagina_web }}</td>
                            <td>{{ $empresa->updated_at }}</td>
                            <td>{{ $empresa->created_at }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

@endsection
