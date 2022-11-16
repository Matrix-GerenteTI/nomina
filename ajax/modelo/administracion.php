<?php

require_once $_SERVER['DOCUMENT_ROOT']."/nomina/ajax/clases/DB.php";


class Administracion extends DB
{
    public function getDepartamentos()
    {
        $queryDepartamentos = "SELECT * FROM  cdepartamento WHERE status = 1";
        return $this->select( $queryDepartamentos );
    }

}
