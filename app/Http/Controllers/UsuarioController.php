<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UsuarioController extends Controller
{
    public function index()
    {
        $usuarios = User::all();
        return view('usuario.index', compact('usuarios'));
    }
    public function create()
    {
        return view('usuario.crear');
    }
    public function store(Request $request)
    {
        $validacion_datos = $request->validate([
            'name' => "required",
            'email' => "required",
            'password' => "required"
        ]);
        $userexist = User::where("email", $request->email)->first();
        if ($userexist) {
            return back()->with("error", "el correo ya esta registrado");
        }
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $contra = Hash::make($request->password);
        $user->password = $contra;
        $user->rol = "administrador";
        $user->estado = "activo";
        $user->save();
        return redirect('/dashboard/usuario');
    }
    public function show($id)
    {
    }
    public function edit($id)
    {
        $usuario = User::findOrFail($id);
        return view('usuario.editar', compact('usuario'));
    }
    public function update(Request $request, $id)
    {
        try {
            $validacion_datos = $request->validate([
                'name' => "required",
                'email' => "required",
                "estado" => "required",
            ]);
            $user = User::findOrFail($id);
            if (isset($request->password)) {
                if ($request->password != "") {
                    $contra = Hash::make($request->password);
                    $user->password = $contra;
                }
            }
            $user->name = $request->name;
            $user->email = $request->email;
            $user->estado = $request->estado;
            $user->save();
            return redirect('/dashboard/usuario');
        } catch (Exception $e) {
            return back()->with("error", $e->getMessage());
        }
    }
    public function destroy($id)
    {
        $usuario = User::findOrFail($id);
        $usuario->estado = "baja";
        $usuario->save();
        return redirect('/dashboard/usuario');
    }
}
