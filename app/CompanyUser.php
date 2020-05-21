<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

// Modelo asociado a la tabla control_users
class CompanyUser extends Model
{
    //
    protected $table = "clientes";

    protected $fillable = [ 'nombres', 'apellidos', 'telefono', 'saldo' ];

}
