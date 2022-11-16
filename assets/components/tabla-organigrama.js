Vue.component("tr-organigrama", {
    props:['puestos'],
    template: ` <tbody>
                        <tr v-for="puesto in puestos" >
                            <td></td>
                            <td>{{puesto.puestoHijo}}</td>
                            <td>{{puesto.puestoPadre}}</td>
                            <td ><i class="shortcut-icon icon-remove btn btn-danger" style="cursor:pointer" @click="eliminar(puesto.id)"></i></td>
                         </tr>
                    </tbody>
                    `,
    methods:{
        eliminar:function (id) { 
            axios.get('ajax/ajaxempresa.php',{
                params:{
                    op: 'eliminarNodoOrganigrama',
                    nodo: id
                }
            }).then((result) => {
                if ( result.data > 0) {
                    vmPuestos.getOrganigrama();
                    return 0;
                }
                alert("No se pudo eliminar, intente nuevamente");
            }).catch((err) => {
                
            });
         }
    }
})