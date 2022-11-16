<?php 

require_once $_SERVER['DOCUMENT_ROOT']."/nomina/ajax/clases/DB.php";


class Empleados extends DB 
{
    public function getEmpleadosActivos( $departamento, $puesto, $nombre)
    {

        $queryEmpleados = "SELECT   pempleado.nip, 
                                    pempleado.nombre as nombre,
                                    cdepartamento.descripcion as departamento,
                                    cpuesto.descripcion as puesto
                           FROM     pempleado
                           INNER JOIN pcontrato on pcontrato.nip = pempleado.nip
                           INNER JOIN csucursal on csucursal.id = pempleado.idsucursal
                           INNER JOIN cdepartamento on cdepartamento.id = pcontrato.iddepartamento
                           WHERE   pempleado.status = 1
                           AND     csucursal.id LIKE '%$sucursal%'
                           AND     cdepartamento.id LIKE '%$departamentoId%'
                           AND     cpuesto.id LIKE '%$puesto%'
                           AND     pempleado.nombre LIKE '%$nombre%' 
                           ORDER BY csucursal.descripcion,cdepartamento.descripcion,cpuesto.descripcion,pempleado.nombre ";

        return $this->select( $queryEmpleados );
    }

    public function getEmpleadoByDepartamento( $nombreTrabajador, $departamentoId)
    {

        $queryEmpleados = "SELECT pempleado.nip, pempleado.nombre,cdepartamento.descripcion as departamento
                                            FROM pempleado
                                            INNER JOIN pcontrato on pcontrato.nip = pempleado.nip
                                            INNER JOIN cdepartamento on cdepartamento.id = pcontrato.iddepartamento
                                            WHERE (pempleado.nombre LIKE '%$nombreTrabajador%'  OR  pempleado.nombre = '$nombreTrabajador' ) AND pempleado.status = 1
                                            AND  cdepartamento.id LIKE '%$departamentoId%' ";

        return $this->select( $queryEmpleados );
    }

    public function getPersonalDataEmpleado($empleado)
    {
        $queryEmpleado = "SELECT pempleado.nip,pempleado.nombre,pempleado.fechanac,pempleado.edocivil,
                                                        pdireccion.calle, pdireccion.numext,pdireccion.numint,pdireccion.colonia,pdireccion.municipio,pdireccion.cp
                                    FROM pempleado
                                    INNER JOIN pdireccion ON pdireccion.nip = pempleado.nip
                                    WHERE  (pempleado.nip ='$empleado' || pempleado.nombre LIKE '%$empleado%'  ) AND pempleado.status = 1";
        return $this->select( $queryEmpleado );
    }

    public function registraFamiliarEmpleado( $query )
    {
        $queryFamiliar = "INSERT INTO cfamiliares_empleados VALUES $query ";
        echo $queryFamiliar;
        return $this->insert( $queryFamiliar );
    }
    public function actualizaDatosPersonales( $parametros )
    {
        extract( $parametros );
        $queryDatosPersonales = "UPDATE pempleado SET nombre='$empleado' ,
                                        fechanac='$fechaNac',edocivil='$edoCivil'  
                                        WHERE nip = $nip";
        return $this->update( $queryDatosPersonales );
    }

    public function actualizaDireccionTrabajador( $parametros )
    {
        extract( $parametros );
        $querySetDireccion = "UPDATE  pdireccion SET calle='$calle',numext='$numExt',
                                    numint='$numInt',colonia='$colonia', municipio='$municipio',cp='$cp'
                                    WHERE  nip = $nip ";
        return $this->update( $querySetDireccion );
    }

    public function agregaLugarNacimiento( $parametros )
    {
        extract( $parametros );
        $queryNacimiento = "INSERT INTO pevaluacion_socioeconomico VALUES(1,$socioeconomico,'$lugarNac') ";
        // echo $queryNacimiento;
        return $this->insert( $queryNacimiento);
    }
}
