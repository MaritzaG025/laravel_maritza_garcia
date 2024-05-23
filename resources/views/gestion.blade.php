@extends('layouts.app')

@section('titulo', 'Gestión')

@section('contenido')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title" style="color: #00BD56;"><strong>EMPRESAS</strong></h3>
                        <div class="d-flex justify-content-center mb-3">
                            <img src="/images/empresas_sis_g.png" class="card-img-top img-fluid" alt="Imagen de empresas"
                                style="width: 100%; height: 250px;">
                        </div>
                        <p class="card-text">
                            En esta sección, tendrás acceso a todas las empresas aliadas a nuestro proyecto, así como la
                            capacidad de crear nuevas empresas. Gestiona fácilmente la información de las empresas. Con
                            nuestro sistema de gestión de empresas, puedes mantener un registro
                            organizado y actualizado de todas las entidades asociadas a nuestro proyecto.
                        </p>
                        <a href="{{ route('empresas.index') }}" class="btn btn_verde d-block mx-auto">Gestionar empresas</a>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title" style="color: #00BD56;"><strong>USUARIOS</strong></h3>
                        <div class="d-flex justify-content-center mb-3">
                            <img src="/images/usuarios_sis_g.jpg" class="card-img-top img-fluid" alt="Imagen de usuarios"
                                style="width: 100%; height: 250px;">
                        </div>
                        <p class="card-text">
                            En esta sección, podrás gestionar todos los usuarios asociados a nuestro proyecto, de acuerdo a la
                            empresa seleccionada. Desde aquí, podrás crear nuevos usuarios y administrar los existentes.
                            Mantengamos un control total sobre quién tiene acceso, a que empresa pertenecen y qué permisos tienen dentro de nuestra
                            plataforma.
                        </p>
                        <a href="{{ route('usuarios.index') }}" class="btn d-block mx-auto btn_verde">Gestionar usuarios</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
