<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <br/><br/><br/>
                <div class='alert alert-warning'>
                    <p>Solo está permitida la subida de <strong>ficheros .txt</strong></p>
                </div>
            </div>
        </div>
        <div v-if="peticion_realizada" class="row justify-content-center">
            <div class="col-md-10">
                <hr/>
                <div class="alert alert-info">
                    {{ mensaje_peticion_realizada }}
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-10">
            <hr/>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="inputAddon">Agregar a la subida</span>
                </div>
                <div class="custom-file">
                    <input @change="addFile" type="file" style="cursor: pointer;" class="custom-file-input" id="inputFile"
                    aria-describedby="inputAddon">
                    <label class="custom-file-label" for="inputFile">Seleccionar fichero</label>
                </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class='col-md-10'>
                <ul class='list-group' style='width: 100%;'>
                    <div class='row' v-if="archivos_agregados.length == 0">
                        <div class='col-md-12'>
                            <hr/>
                            <li class='list-group-item'>
                                <p> Aun no has agregado ningún fichero. </p>
                            </li>
                        </div>
                    </div>
                    <div class='row' v-for="( archivo_agregado, index ) in archivos_agregados">
                        <div class='col-md-12'>
                            <hr v-if="index == 0" />
                            <br v-if="index != 0" />
                            <li class='list-group-item'>
                                <p> {{ archivo_agregado['nombre'] }} <button @click="deleteFileBeforeUpload( index )" class='btn btn-danger' style='float: right;'> X </button> </p>
                            </li>
                        </div>
                    </div>
                </ul>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-10">
                <hr/>
                <button type="submit" @click="saveAllFiles" class="btn btn-primary btn-block"> Guardar registros procedentes de los archivos agregados </button>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data : function(){
            return {
                archivos_agregados : [
                    
                ],
                peticion_realizada : false,
                mensaje_peticion_realizada : ""
            };
        },
        methods : {
            addFile : function( e ){
                var fileName = e.target['files'][0].name, fileType = e.target['files'][0].type, file = e.target['files'][0];
                var filesAllowed = [ 'text/plain' ];
                filesAllowed.forEach( ( fileAllowed ) => {
                    if( fileAllowed.localeCompare( String(fileType) ) == 0 ){ 
                        this.archivos_agregados.push( { nombre : fileName, fichero : file  } ); 
                    }else{
                        alert("El tipo de fichero que intenta subir no está permitido");
                    }
                } );
               e.target.value = '';
            },
            deleteFileBeforeUpload : function( index ){
                 this['archivos_agregados'].splice( index, 1 );
            },
            saveAllFiles : function( e ){
                var formData = new FormData();
                if(  this.archivos_agregados.length > 0 ){
                    this.archivos_agregados.forEach( ( archivo, index ) => {
                        formData.append(['archivo' + index], archivo['fichero']);
                    } );
                    if( axios ) axios.post('api/users', formData,  { headers: {  'Content-Type': 'multipart/form-data' } }).then( ( rps ) => {
                        this.peticion_realizada = true;
                        var numero_usuarios_agregados = ( rps['data']['usuarios_agregados_con_exito'] ) ? rps['data']['usuarios_agregados_con_exito'] : -1;
                        switch( numero_usuarios_agregados ){
                            case -1:
                                this.mensaje_peticion_realizada = "Parece que ha ocurrido un error en el servidor a la hora de realizar la petición";
                            break;
                            case 0:
                                this.mensaje_peticion_realizada = "No se ha insertado ningún registro. Puede que el archivo se encontrara vacío o se haya producido algún error en el servidor.";
                            break;
                            default:
                                this.mensaje_peticion_realizada = "Se han agregado " + numero_usuarios_agregados + " clientes correctamente." ;
                            break;
                        }
                    } );
                    this.archivos_agregados = [];
                }else{
                    alert("Debe elegir un fichero como mínimo");
                }
            }
        },
        computed : function(){
            return {};
        }
    }
</script>
