<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Empresa;
use App\Models\Usuario;

class UsuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Se cargan todas las empresas para el select del index de usuarios, si existe un error devolver el msj
        $empresas = Empresa::all();
        $errorMessage = $request->query('error_message');
        return view('usuarios.index', ['empresas' => $empresas, 'errorMessage' => $errorMessage]);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        // Crear el usuario de acuerdo al id de la empresa
        $empresaId = $request->input('empresa_id');
        // Controlar el error en dado caso que el id cambie en la url
        $empresa = Empresa::where('id_empresas', $empresaId)->first();
        if (!$empresa) {
            $errorMessage = 'La empresa no existe.';
            $empresas = Empresa::all();
            return redirect()->route('usuarios.index', ['error_message' => $errorMessage]);
        }
        return view('usuarios.create', compact('empresaId'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Verificación de que no exista email y usuario (estos deben ser unicos)
        $existingEmail = Usuario::where('email', $request->email)->first();
        $existingUser = Usuario::where('usuario', $request->usuario)->first();
        if ($existingEmail) {
            if ($existingEmail && $existingUser) {
                return response()->json(['errorMessage' => 'Ya existe un usuario con este correo electrónico y este nombre de usuario.']);
            }
            return response()->json(['errorMessage' => 'Ya existe un usuario con este correo electrónico.']);
        }else if ($existingUser) {
            return response()->json(['errorMessage' => 'Ya existe un usuario con este nombre de usuario.']);
        }

        // Crear y guardar el nuevo usuario. Y devolver mensaje de exito
        $usuario = new Usuario();
        $usuario->nombre = $request->nombre;
        $usuario->email = $request->email;
        $usuario->usuario = $request->usuario;
        $usuario->tipo_usuario = $request->tipo_usuario;
        $usuario->contrasen_a = $request->contrasena;
        $usuario->activo = $request->activo;
        $usuario->id_empresa = $request->empresa_id;

        $usuario->save();
        return response()->json(['successMessage' => 'El usuario se creó correctamente.']);
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

    // para obtener los usuarios por empresa
    public function getUsersByCompany(Request $request)
    {
        $empresaId = $request->input('empresa_id');
        if (!$empresaId) {
            return response()->json(['error' => 'ID de empresa no proporcionado'], 400);
        }

        // Realiza la consulta para obtener usuarios basados en el ID de la empresa
        $usuarios = Usuario::where('id_empresa', $empresaId)->get();

        // Verifica si hay usuarios para la empresa
        if ($usuarios->isEmpty()) {
            return response()->json(['usuarios' => []]);
        }

        return response()->json(['usuarios' => $usuarios]);
    }


}
