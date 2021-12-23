<?php

namespace App\Http\Controllers;

use App\Imports\TransportistaImport;
use App\Models\Transportista;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use SimpleXLSX;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\HeadingRowImport;

class TransportistaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $transportistas = Transportista::all();
        return view('transportistas.index', compact("transportistas"));
    }
    public function importarExcel(Request $request)
    {
        try {
            $validation = $request->validate([
                "exceltransportistas" => "required",
            ]);
            try {
                $file = $request->file("exceltransportistas");
                Excel::import(new TransportistaImport(), $file);
                return back()->with('success', 'Todo bien!');
            } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
                $failures = $e->failures();
                $rows = "";
                $atributos = "";
                foreach ($failures as $failure) {
                    $rows = $failure->row();
                    $atributos .=  $failure->attribute() . ",\n";
                }
                return back()->with(["error" => true, "error.row" => $rows, "error.columns" => $atributos]);
            }
        } catch (Exception $e) {
            return back()->with(["exception" => true, "exception.message" => $e->getMessage()]);
        }
    }
    public function create()
    {
        return view('transportistas.crear');
    }
    public function store(Request $request)
    {
        try {
            $validacion_datos = $request->validate([
                'sap' => "required",
                'nombre' => "required",
                'correo' => "required",
                'password' => "required"
            ]);
            $userexist = Transportista::where("sap", $request->sap)->first();
            if ($userexist) {
                return back()->with("error", "El sap indicado ya esta registrado");
            }
            $transportista = new Transportista();
            $transportista->sap = $request->sap;
            $transportista->nombre = $request->nombre;
            $transportista->correo = $request->correo;
            $transportista->usuario = $request->sap;
            $contra = Hash::make($request->password);
            $transportista->password = $contra;
            $transportista->save();
            return redirect('/dashboard/transportista');
        } catch (Exception $e) {
            return back()->with("error",$e->getMessage());
        }
    }
    public function show($id)
    {
    }
    public function edit($id)
    {
        $transportista = Transportista::findOrFail($id);
        return view('transportistas.editar', compact('transportista'));
    }
    public function update(Request $request, $id)
    {
        try {
            $validacion_datos = $request->validate([
                'sap' => "required",
                'nombre' => "required",
                'correo' => "required"
            ]);
            $user = Transportista::findOrFail($id);
            if (isset($request->password)) {
                if ($request->password != "") {
                    $contra = Hash::make($request->password);
                    $user->password = $contra;
                }
            }
            $user->sap = $request->sap;
            $user->nombre = $request->nombre;
            $user->correo = $request->correo;
            $user->usuario = $request->sap;
            $user->save();
            return redirect('/dashboard/transportista');
        } catch (Exception $e) {
            return back()->with("error", $e->getMessage());
        }
    }
    public function destroy($id)
    {
        $usuario = Transportista::findOrFail($id);
        $usuario->estado = "baja";
        $usuario->save();
        return redirect('/dashboard/transportista');
    }
}
