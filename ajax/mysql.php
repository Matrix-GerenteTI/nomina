<?php
if(!isset($_SESSION)){
    session_start(); 
}
require_once("config.inc.php");


$conexion = new mysqli(HOST,USER,PASSWORD,BD);
//$bd = mysql_select_db(BD,$conexion);


?>
