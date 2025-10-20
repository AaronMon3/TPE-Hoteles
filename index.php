<?php
session_start();
require 'config.php';

function url($path = '') {
    return BASE_URL . '/' . ltrim($path, '/');
}

function redirect($path = '') {
    header('Location: ' . url($path));
    exit;
}

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
