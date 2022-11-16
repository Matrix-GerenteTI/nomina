<?php

require_once $_SERVER['DOCUMENT_ROOT']."/nomina/ajax/modelo/empleados.php";

class EmpleadosController  
{
    public function getEmpleadoByDepartamento( $nombreTrabajador, $departamentoId)
    {
        $empleados = new Empleados;
        return $empleados->getEmpleadoByDepartamento( $nombreTrabajador, $departamentoId );
    }   

    public function getPersonalDataEmpleado( $empleado )
    {
        $empleados = new Empleados;
        $listaEmpleado = $empleados->getPersonalDataEmpleado( $empleado );
        foreach ($listaEmpleado as $i => $trabajador) {
            $fechaNacExpllode = explode( "-", $trabajador['fechanac'] );
            //$listaEmpleado[$i]['fechanac'] = $fechaNacExpllode[2]."/".$fechaNacExpllode[1]."/".$fechaNacExpllode[0];
        }

        return $listaEmpleado;
    }

    public function registraFamiliarDeEmpleado($empleado, $familiares)
    {
        $queryFamiliares = "";
        foreach ($familiares as $familiar) {
            $queryFamiliares .="('','$familiar->nombre','$familiar->parentesco','$familiar->edad','$familiar->gradoEscolar','$familiar->ocupacion','$empleado'),";
        }
        $empleados = new Empleados;
        
        return $empleados->registraFamiliarEmpleado( $queryFamiliares);
    }
}

$opc = isset( $_GET['opc'] ) ? $_GET['opc'] : $_POST['opc'] ;

switch ($opc) {
    case 'getEmpleadoDepartamento':
         echo json_encode( EmpleadosController::getEmpleadoByDepartamento($_GET['trabajador'], $_GET['departamento']) );
        break;
    case 'getPersonalData':
        echo json_encode( EmpleadosController::getPersonalDataEmpleado($_GET['trabajador']) );
        break;
    case 'guardaFamiliares':
            $datosFamiliares =json_decode( $_POST['familiares']);
            echo EmpleadosController::registraFamiliarDeEmpleado( $_POST['empleado'], $datosFamiliares);
        break;
    default:
        # code...
        break;
}