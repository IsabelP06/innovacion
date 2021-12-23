<?php

namespace App\Imports;

use App\Models\Transportista;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
class TransportistaImport implements ToModel,WithHeadingRow,WithValidation
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Transportista([
            "sap"=>$row["sap"],
            "nombre"=>$row["nombre"],
            "correo"=>$row["correo"]?$row["correo"]:"",
            "usuario"=>$row["usuario"],
            "password"=>Hash::make($row["password"])
        ]);
    }
    public function rules():array {
        return [
            'sap'=>'required',
            'nombre'=>'required',
            'usuario'=>'required',
            'password'=>'required'
        ];
    }
}
