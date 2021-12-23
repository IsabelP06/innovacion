<?php

namespace App\Http\Controllers;

use App\Models\Sede;
use Exception;
use Illuminate\Http\Request;

class SedeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sedes=Sede::all();
        return view("sede.index",compact("sedes"));
    }
    public function create()
    {
        return view('sede.crear');
    }
    public function store(Request $request)
    {
        $validacion_datos = $request->validate([
            'nombre' => "required",
        ]);
        $sede = new Sede();
        $sede->nombre = $request->nombre;
        $sede->save();
        return redirect('/dashboard/sedes');
    }
    public function show($id)
    {
    }
    public function edit($id)
    {
        $sede = Sede::findOrFail($id);
        return view('sede.editar', compact('sede'));
    }
    public function update(Request $request, $id)
    {
        try {
            $validacion_datos = $request->validate([
                'nombre' => "required",
            ]);
            $sede = Sede::findOrFail($id);
            $sede->nombre = $request->nombre;
            $sede->save();
            return redirect('/dashboard/sedes');
        } catch (Exception $e) {
            return back()->with("error", $e->getMessage());
        }
    }
    public function destroy($id)
    {
        $sede = Sede::findOrFail($id);
        $sede->delete();
        return redirect('/dashboard/sedes');
    }
}
