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
        $empresas = Empresa::all();
        return view('empresas.index', compact('empresas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
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
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
