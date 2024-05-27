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
                        <th>Clave</th>
                        <th>Nombre</th>
                        <th>Fecha de Creación</th>
                        <th>Fecha de Actualización</th>
                        <th>Modificar</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($empresas as $empresa)
                        <tr>
                            <td>{{ $empresa->clave_empresa }}</td>
                            <td>{{ $empresa->nombre_empresa }}</td>
                            <td>{{ $empresa->created_at }}</td>
                            <td>{{ $empresa->updated_at }}</td>
                            <td><a href="/empresas/{{$empresa->clave_empresa}}" class="btn btn-outline-success me-3 w-100">Modificar</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

@endsection
