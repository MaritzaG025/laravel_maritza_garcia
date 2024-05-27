<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    use HasFactory;

    // Especificar la columna de la clave primaria
    protected $primaryKey = 'id_empresas';

    // Indicar si la clave primaria es autoincremental
    public $incrementing = true;

    // Especificar el tipo de la clave primaria
    protected $keyType = 'int';

    // Indicar los campos que se pueden asignar en masa
    protected $fillable = [
        'clave_empresa',
        'tipo_identificacion',
        'identificacion',
        'nombre_empresa',
        'razon_social',
        'email',
        'telefono',
        'direccion',
        'pagina_web',
    ];
}
