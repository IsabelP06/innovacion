<?php

namespace App\Http\Controllers;

use App\Models\CorreoConfig;
use Exception;
use Illuminate\Http\Request;

class CorreoConfigController extends Controller
{
    public function index()
    {
        $correos=CorreoConfig::all();
        
        return view("config.correos",compact("correos"));
    }
    public function create()
    {
        return view('config.crearcorreos');
    }
    public function store(Request $request)
    {
        $validacion_datos = $request->validate([
            'correo' => "required",
        ]);
        $correoconfig = new CorreoConfig();
        $correoconfig->correo = $request->correo;
        $correoconfig->save();
        return redirect('/dashboard/config/correos');
    }
    public function show($id)
    {

    }
    public function edit($id)
    {
        $correo = CorreoConfig::findOrFail($id);
        return view('config.editarcorreo', compact('correo'));
    }
    public function update(Request $request, $id)
    {
        try {
            $validacion_datos = $request->validate([
                'correo' => "required",
            ]);
            $configcorreo = CorreoConfig::findOrFail($id);
            $configcorreo->correo = $request->correo;
            $configcorreo->save();
            return redirect('/dashboard/config/correos');
        } catch (Exception $e) {
            return back()->with("error", $e->getMessage());
        }
    }
    public function destroy($id)
    {
        $configcorreo = CorreoConfig::findOrFail($id);
        $configcorreo->delete();
        return redirect('/dashboard/config/correos');
    }
}
