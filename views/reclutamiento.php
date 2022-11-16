<?php $this->layout("master") ?>


<?php $this->push("styles") ?>
    <link href="/nomina/css/bootstrap/bootstrap.min.css" rel="stylesheet">
<?php $this->end() ?>
<?php $this->push("maincontent") ?>
<div class="container" id="recruitment">
    <div class="row">
        <div class="col-12">

        <form>
        <div class="form-row">
            <div class="form-group col-3">
                <label >Nombre(s)</label>
                <input type="text" class="form-control" v-model="nombre"  placeholder="Escribe tu nombre...">
            </div>
            <div class="form-group col-3">
                <label >Apellido Paterno</label>
                <input type="text" class="form-control"  v-model="paterno" placeholder="Apellido paterno">
            </div>
        <div class="form-group col-3">
            <div class="form-group">
                <label >Apellido Materno</label>
                <input type="text" class="form-control"  v-model="materno" placeholder="Apellido materno">
            </div>
        </div>
        <div class="form-group col-3">
            <div class="form-group">
                    <label >Fecha de nacimiento</label>
                    <input type="text" class="form-control" v-model="nacimiento"  placeholder="fecha nacimiento">
            </div>    
        </div>
        </div>
        <div class="form-row">
            <div class="form-group col-3">
                <label for="inputAddress2">Curp</label>
                <input type="text" class="form-control" v-model="curp"  placeholder="XXXXX000000XXXXXX">
            </div>
            <div class="form-group col-3">
            <label >RFC</label>
            <input type="text" v-model="rfc" class="form-control">
            </div>
            <div class="form-group col-6">
            <label for="">NSS</label>
            <input type="text"  v-model="nss" class="form-control" >
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-3">
                <label for="">Calle</label>
                <input type="text"  v-model="calle" class="form-control">
            </div>
            <div class="form-group col-1">
                <label for="">No. Ext.</label>
                <input type="text" class="form-control" v-model="ext">
            </div>
            <div class="form-group col-1">
                <label for="">No. Int.</label>
                <input type="text" class="form-control" v-model="int">
            </div>
            <div class="form-group col-2">
                <label for="">Colonia</label>
                <input type="text" class="form-control" v-model="colonia">
            </div>    
            <div class="form-group col-2">
                <label for="">Estado</label>
                <input type="text" class="form-control" v-model="ciudad">
            </div>    
            <div class="form-group col-3">
                <label for="">Municipio</label>
                <input type="text" class="form-control" v-model="municipio" >
            </div>
        </div>
            <div class="form-row">
                <div class="form-group col-6">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" v-model="tieneLicencia"  value="no">
                        <label class="form-check-label" for="inlineRadio1">No</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" v-model="tieneLicencia"  value="si" >
                        <label class="form-check-label" for="inlineRadio2">sí</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" v-model="licencias" value="chofer"  >
                        <label class="form-check-label" for="inlineRadio3">Chófer</label>
                    </div>            
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" v-model="licencias" value="moticiclista"  >
                        <label class="form-check-label" for="inlineRadio3">Motociclista</label>
                    </div>                    
                </div>

                <div class="form-group col-6">
                        <label for="">Experiencia laboral</label>
                        <table>
                                <th>
                                    <div class="form-group">
                                        <label for="">Empresa</label>
                                        <input type="text" class="form-control" v-model="exp_empresa">
                                    </div>                            
                                </th>
                                <th>
                                    <div class="form-group">
                                        <label for="">Puesto</label>
                                        <input type="text" class="form-control" v-model="exp_puesto">
                                    </div>                            
                                </th>
                                <th>
                                    <div class="form-group">
                                        <label for="">Duración</label>
                                        <input type="text" class="form-control" v.model="exp_duracion">
                                    </div>                            
                                </th>   
                                <th>
                                    <div class="form-group">
                                        <br>
                                        <button class="btn btn-default"><i class="icon-plus"></i></button>
                                    </div>
                                </th>                                             
                        </table>
                </div>
            </div>
    <button type="button" class="btn btn-dark" @click="register()"> Registrar</button>
  <!-- <button type="submit" class="btn btn-primary">Sign in</button> -->
</form>

        </div>
    </div>
</div> 
<?php $this->end() ?>

<?php $this->push("scripts") ?>
    <script src="/nomina/js/rh/reclutamiento.js"></script>
<?php $this->end() ?>