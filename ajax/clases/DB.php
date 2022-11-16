<?php

require_once $_SERVER['DOCUMENT_ROOT']."/nomina/ajax/config.inc.php";


class DB
{
    protected $conexion;

    public function __construct()
    {
        $this->conexion = new mysqli(HOST,USER,PASSWORD,BD);
        $this->conexion->query("SET NAMES 'utf8' ");
    }

    public function select($query)
    {
        $exeQuery = $this->conexion->query($query);
        return  $exeQuery->fetch_all(MYSQLI_ASSOC);
    }

    public function insert( $query)
    {
        $exeQuery = $this->conexion->query( $query );
        if ( $exeQuery ) {
            return $this->conexion->insert_id;
        }else{
            return -1;
        }
    }

    public function update( $query)
    {
        $exeQuery = $this->conexion->query( $query );
        return $this->conexion->affected_rows;
    }
}
