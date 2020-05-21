<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <h3 align="center" class="justify-content-center">Datos - clientes registrados</h3>
                <br/>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th> Nombre </th>
                            <th> Apellidos </th>
                            <th> Teléfono </th>
                            <th> Saldo </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-if="users.length == 0">
                            <td>No se han agregado usuarios aún.</td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr v-for="user in users">
                            <td>{{ user["nombres"] }}</td>
                            <td>{{ user["apellidos"] }}</td>
                            <td>{{ user["telefono"] }}</td>
                            <td>{{ user["saldo"] }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data : function(){
            return {
                users : [
                    
                ]
            }
        },
        mounted : function(){
            if( axios )
            axios.get('./api/users').then( ( rps ) => {
                this.users = rps['data'];
            } );
        },
        computed : function(){
            return {};
        }
    }
</script>
