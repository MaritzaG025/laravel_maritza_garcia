<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use Illuminate\Http\Request;

class EmpresaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Traer todas las empresas
        $empresas = Empresa::all();
        return view('empresas.index', compact('empresas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // retorne la vista de crear
        return view('empresas.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Verificar si la clave de la empresa ya existe
        $existingCompany = Empresa::where('clave_empresa', $request->input('clave_empresa'))->exists();

        // Si la clave de la empresa ya existe, devolver un mensaje de error
        if ($existingCompany) {
            return response()->json(['errorMessage' => 'Hubo un error al crear la empresa. La clave ya existe, por favor verifique y escriba otra.']);
        }

        // Si no, guardar la empresa y devolver mensaje de exito
        $company = new Empresa();
        $company->clave_empresa = $request->input('clave_empresa');
        $company->tipo_identificacion = $request->input('tipo_identificacion');
        $company->identificacion = $request->input('identificacion');
        $company->nombre_empresa = $request->input('nombre_empresa');
        $company->razon_social = $request->input('razon_social');
        $company->email = $request->input('email');
        $company->telefono = $request->input('telefono');
        $company->direccion = $request->input('direccion');
        $company->pagina_web = $request->input('pagina_web');

        $company->save();
        return response()->json(['successMessage' => 'Empresa creada con éxito']);
    }

    /**
     * Display the specified resource.
     */
    public function show($clave_empresa)
    {
        // buscar la empresa primero por la clave
        $company = Empresa::where('clave_empresa', $clave_empresa)->first();

        // verificar si existe y devolver el mensaje
        if (!$company) {
            return view('empresas.show')->with([
                'company' => new Empresa(),
                'showErrorModal' => true
            ]);
        }

        return view('empresas.show')->with([
            'company' => $company,
            'showErrorModal' => false
        ]);
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit($clave_empresa)
    {
        $company = Empresa::where('clave_empresa', $clave_empresa)->first();

        if (!$company) {
            return view('empresas.show')->with([
                'company' => new Empresa(),
                'showErrorModal' => true
            ]);
        }

        return view('empresas.edit')->with([
            'company' => $company,
            'showErrorModal' => false
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $clave_empresa)
    {
        // Obtener la empresa por su clave
        $empresa = Empresa::where('clave_empresa', $clave_empresa)->first();

        // Verificar si la empresa existe
        if (!$empresa) {
            // Si la empresa no existe, mostrar un mensaje de error
            return response()->json(['errorMessage' => 'Empresa no encontrada.']);
        }

        // Actualizar los campos de la empresa con los datos del formulario
        $empresa->nombre_empresa = $request->nombre_empresa;
        $empresa->tipo_identificacion = $request->tipo_identificacion;
        $empresa->identificacion = $request->identificacion;
        $empresa->razon_social = $request->razon_social;
        $empresa->direccion = $request->direccion;
        $empresa->email = $request->email;

        $empresa->telefono = $request->telefono;
        $empresa->pagina_web = $request->pagina_web;

        // Intentar guardar los cambios en la base de datos y devolver el msj
        if ($empresa->save()) {
            return response()->json(['successMessage' => 'La empresa se actualizó correctamente.']);
        } else {
            return response()->json(['errorMessage' => 'Hubo un error al actualizar la empresa.']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($clave_empresa)
    {
        $empresa = Empresa::where('clave_empresa', $clave_empresa)->first();

        if (!$empresa) {
            return response()->json(['errorMessage' => 'Empresa no encontrada'], 404);
        }

        if ($empresa->delete()) {
            return response()->json(['successMessage' => 'Empresa eliminada correctamente']);
        } else {
            return response()->json(['errorMessage' => 'No se pudo eliminar la empresa'], 500);
        }
    }
}
