<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Transportista extends Authenticatable
{
    use HasFactory;
    public $table = "transportista";
    public $primaryKey = "id";

    protected $fillable = [
        'etapa',
        'nr',
        'sap',
        'nombre',
        'correo',
        'usuario',
        'password'
    ];
    protected $hidden = [
        'password',
        
    ];
}
