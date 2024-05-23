<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    use HasFactory;
    // Asegurar de usar la tabla de usuarios de la BD
    protected $table = 'usuarios';
}
