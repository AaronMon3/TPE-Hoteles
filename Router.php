<?php
require_once 'config.php';
require_once 'app/controllers/AdminController.php';
require_once 'app/controllers/HabitacionController.php';
require_once 'app/controllers/TipoHabitacionController.php';
require_once 'app/models/HabitacionModel.php';
require_once 'app/models/TipoHabitacionModel.php';
require_once 'app/models/UsuarioModel.php';

if(!empty($_GET["action"])){
    $action = $_GET["action"];
} else {
    $action = "home";
}

$params = explode("/", $action);

switch ($params[0]){
    // Públicas
    case 'home':
        $habitacion = new HabitacionController($pdo);
        $habitacion->listar();
        break;
    
    case 'habitaciones':
        if(isset($params[1])){
            $_GET['id'] = $params[1];
            $habitacion = new HabitacionController($pdo);
            $habitacion->detalle();
        } else {
            $habitacion = new HabitacionController($pdo);
            $habitacion->listar();
        }
        break;
    
    case 'tipos':
        if(isset($params[1])){
            $_GET['id_tipo'] = $params[1];
            $habitacion = new HabitacionController($pdo);
            $habitacion->porTipo();
        } else {
            $tipo = new TipoHabitacionController($pdo);
            $tipo->listar();
        }
        break;
    
    // Admin
    case 'admin':
        $admin = new AdminController($pdo);
        if(!isset($params[1])){
            $admin->login();
        } else {
            switch($params[1]){
                case 'login':
                    $admin->login();
                    break;
                case 'logout':
                    $admin->logout();
                    break;
                case 'dashboard':
                    $admin->dashboard();
                    break;
                case 'habitaciones':
                    if(isset($params[2])){
                        switch($params[2]){
                            case 'nueva':
                                $admin->formHabitacion();
                                break;
                            case 'editar':
                                if(isset($params[3])){
                                    $_GET['id'] = $params[3];
                                }
                                $admin->formHabitacion();
                                break;
                            case 'eliminar':
                                if(isset($params[3])){
                                    $_GET['id'] = $params[3];
                                }
                                $admin->eliminarHabitacion();
                                break;
                        }
                    } else {
                        $admin->listaHabitaciones();
                    }
                    break;
                case 'tipos':
                    if(isset($params[2])){
                        switch($params[2]){
                            case 'nuevo':
                                $admin->formTipo();
                                break;
                            case 'editar':
                                if(isset($params[3])){
                                    $_GET['id'] = $params[3];
                                }
                                $admin->formTipo();
                                break;
                            case 'eliminar':
                                if(isset($params[3])){
                                    $_GET['id'] = $params[3];
                                }
                                $admin->eliminarTipo();
                                break;
                        }
                    } else {
                        $admin->listaTipos();
                    }
                    break;
            }
        }
        break;
    
    default:
        echo "404 - Página no encontrada";
        break;
}
?>
