<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT']."/nomina/vendor/autoload.php";

$base  = dirname($_SERVER['PHP_SELF']);

if(ltrim($base, '/')){ 

    $_SERVER['REQUEST_URI'] = substr($_SERVER['REQUEST_URI'], strlen($base));
}

$route = new \Klein\Klein();

$route->respond("/", function ($request, $response ,$service)
{
    $response->redirect("index.php", 200);       
});

$route->respond("/organigrama", function ($request, $response ,$service)
{
    $response->redirect("/organigrama/index.php", 200);       
});

$route->respond("GET","/reclutamiento", function ( $request , $response , $service)
{
    $templates = new League\Plates\Engine('views');
    echo $templates->render("reclutamiento" );
});


$route->dispatch();










