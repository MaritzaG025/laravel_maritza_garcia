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
    public function show($empresaId, $usuarioId)
    {
        $company = Empresa::where('id_empresas', $empresaId)->first();
        $usuario = Usuario::where('id_usuario', $usuarioId)->first();

        if (!$company && !$usuario) {
            return view('usuarios.show')->with([
                'showErrorModal' => true,
                'errorMessage' => 'No se encontró la empresa ni el usuario.',
                'company' => new Empresa(),
                'usuario' => new Usuario()
            ]);
        }

        if (!$company) {
            return view('usuarios.show')->with([
                'showErrorModal' => true,
                'errorMessage' => 'No se encontró la empresa.',
                'company' => new Empresa(),
                'usuario' => new Usuario()
            ]);
        }

        if (!$usuario || $usuario->id_empresa != $empresaId) {
            return view('usuarios.show')->with([
                'company' => $company,
                'showErrorModal' => true,
                'errorMessage' => 'El usuario no pertenece a esta empresa.',
                'usuario' => new Usuario()
            ]);
        }

        return view('usuarios.show')->with([
            'company' => $company,
            'usuario' => $usuario,
            'showErrorModal' => false
        ]);
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit($empresa_id, $usuario_id)
    {
        $empresa = Empresa::find($empresa_id);
        $usuario = Usuario::find($usuario_id);

        // Verificar si tanto la empresa como el usuario existen
        if (!$empresa || !$usuario) {
            // Si alguno de ellos no existe, redireccionar o mostrar un mensaje de error
            return redirect()->route('usuarios.index')->with('error_message', 'La empresa o el usuario no existen.');
        }

        // Si ambos existen, cargar la vista de edición con los datos de la empresa y el usuario
        return view('usuarios.edit', compact('empresa', 'usuario'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id_usuario)
    {
        $usuario = Usuario::findOrFail($id_usuario);

        // Verificar si el usuario existe
        if (!$usuario) {
            // Si el usuario no existe, mostrar un mensaje de error
            return response()->json(['errorMessage' => 'Usuario no encontrado.']);
        }

        // Verificación de que no exista otro usuario con el mismo email y usuario
        $existingEmail = Usuario::where('email', $request->email)->where('id_usuario', '!=', $id_usuario)->first();
        $existingUser = Usuario::where('usuario', $request->usuario)->where('id_usuario', '!=', $id_usuario)->first();
        if ($existingEmail) {
            if ($existingEmail && $existingUser) {
                return response()->json(['errorMessage' => 'Ya existe un usuario con este correo electrónico y este nombre de usuario.']);
            }
            return response()->json(['errorMessage' => 'Ya existe un usuario con este correo electrónico.']);
        } else if ($existingUser) {
            return response()->json(['errorMessage' => 'Ya existe un usuario con este nombre de usuario.']);
        }

        // Actualizar los campos del usuario con los datos del formulario
        $usuario->nombre = $request->nombre;
        $usuario->email = $request->email;
        $usuario->usuario = $request->usuario;
        $usuario->tipo_usuario = $request->tipo_usuario;

        if ($request->contrasena) {
            $usuario->contrasen_a = $request->contrasena;
        }
        $usuario->activo = $request->activo;

        // Intentar guardar los cambios en la base de datos y devolver msj
        if ($usuario->save()) {
            return response()->json(['successMessage' => 'El usuario se actualizó correctamente.']);
        } else {
            return response()->json(['errorMessage' => 'Hubo un error al actualizar el usuario.']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
// UsuarioController.php

    public function destroy($id_usuario)
    {
        $usuario = Usuario::find($id_usuario);

        if (!$usuario) {
            return response()->json(['errorMessage' => 'Usuario no encontrado'], 404);
        }

        if ($usuario->delete()) {
            return response()->json(['successMessage' => 'Usuario eliminado correctamente']);
        } else {
            return response()->json(['errorMessage' => 'No se pudo eliminar el usuario'], 500);
        }
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
