<?php

namespace App\Http\Controllers;

use DB;
use App\CompanyUser;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;

class apiUploadResourceController extends Controller
{

    public function __construct(){
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

     // Retornamos los registros agregados asociados a un usuario
    public function index()
    {
        return CompanyUser::where('user_id', auth()->id())->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

     // Insertar registros desde archivos txt
    public function store(Request $request)
    {        
        $userId = auth()->id();
        $usuariosAgregados = 0;

        // Recorremos en bucle todos los archivos, para poder leerlos, siempre y cuando se correspondan con el tipo text/plain
        foreach( $request->files as $name => $file  ){
            if( strcmp($request->file( $name )->getMimeType(), "text/plain") == 0 ){
                $textFileContent = $request->file( $name )->get();
                // Separamos por cada línea encontrado en el archivo, para poder insertar individualmente
                foreach( explode( chr(13), $textFileContent) as $register){
                    // Agregamos espacios al final de cada fila, para obtener la longitud total correspondiente a la suma del tamaño máximo de las columnas de un posible registro
                  $fullRegisterElement = str_pad(utf8_encode(substr( $register, 3, 47 )), 47, " ", STR_PAD_RIGHT);
                    $nombres = substr( $fullRegisterElement, 0, 15 );
                    $apellidos = substr( $fullRegisterElement, 15, 15 );
                    $telefono = substr( $fullRegisterElement, 30, 7 );
                    $saldo = ((float)substr( $fullRegisterElement, 37, 10 ));
                    
                    // Asignamos los valores a un nuevo objeto del modelo y lo agregamos
                    $usuario = new CompanyUser();
                    $usuario->user_id = $userId;
                    $usuario->nombres = $nombres;
                    $usuario->apellidos = $apellidos;
                    $usuario->telefono = $telefono;
                    $usuario->saldo = ((int)$saldo);
                    $usuariosAgregados += $usuario->save();
                }
            }
        }

        return [ "usuarios_agregados_con_exito" => $usuariosAgregados ];

    }


}
