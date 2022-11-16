<?php
require_once $_SERVER['DOCUMENT_ROOT']."/nomina/ajax/modelo/administracion.php";

class AdministracionController
{
    
    public function getDepartamentos()
    {
        $administracion  = new Administracion;
        return $administracion->getDepartamentos();
    }
}



$opc = isset( $_GET['opc']) ? $_GET['opc'] : $_POST['opc'];


switch ($opc) {
    case 'getDepartamentos':
            echo json_encode( AdministracionController::getDepartamentos() );
        break;
    
    default:
        # code...
        break;
}