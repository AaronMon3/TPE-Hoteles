<?php
session_start();
require 'config.php';

// El .htaccess ya reescribe las URLs a parámetros GET
// Solo necesitamos leer esos parámetros
$controller = $_GET['controller'] ?? 'habitacion';
$action = $_GET['action'] ?? 'listar';

$controllerFile = "controllers/" . ucfirst($controller) . "Controller.php";
if(file_exists($controllerFile)){
    require $controllerFile;
    $controllerClass = ucfirst($controller) . "Controller";
    $c = new $controllerClass($pdo);
    if(method_exists($c, $action)){
        $c->$action();
    } else {
        echo "Acción no encontrada: " . $action;
    }
} else {
    echo "Controlador no encontrado: " . $controller;
}
?>
