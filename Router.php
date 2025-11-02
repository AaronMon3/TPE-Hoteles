<?php
define('BASE_URL', '//' . $_SERVER['SERVER_NAME'] . ':' . $_SERVER['SERVER_PORT'] . dirname($_SERVER['PHP_SELF']) . '/');

if (!function_exists('url')) {
    function url(string $path = '') {
        $base = rtrim(BASE_URL, '/');
        $index = $base . '/index.php';
        if ($path === '' || $path === '/') {
            return $index;
        }
        if (strpos($path, 'index.php') === 0 || strpos($path, '?') === 0) {
            return $base . '/' . ltrim($path, '/');
        }
        $segments = explode('/', ltrim($path, '/'));
        $enc = array_map('rawurlencode', $segments);
        $pathEncoded = implode('/', $enc);
        return $index . '?action=' . $pathEncoded;
    }
}

require_once 'config.php';

require_once 'app/controllers/adminController.php';
require_once 'app/controllers/habitacionController.php';
require_once 'app/controllers/tipoHabitacionController.php';

$action = 'home';
if (!empty($_GET['action'])) {
    $action = $_GET['action'];
}

$params = explode('/', $action);

switch ($params[0]) {
    case 'home':
        (new HabitacionController())->listar();
        break;

    case 'habitaciones':
    $ctrl = new HabitacionController();
        if (isset($params[1]) && $params[1] !== '') {
            $_GET['id'] = $params[1];
            $ctrl->detalle();
        } else {
            $ctrl->listar();
        }
        break;

    case 'tipos':
    $ctrl = new TipoHabitacionController();
        if (isset($params[1]) && $params[1] !== '') {
            $_GET['id_tipo'] = $params[1];
            (new HabitacionController())->porTipo();
        } else {
            $ctrl->listar();
        }
        break;

    case 'admin':
    $admin = new AdminController();
        if (!isset($params[1]) || $params[1] === '') {
            $admin->login();
            break;
        }

        switch ($params[1]) {
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
                if (isset($params[2])) {
                    if ($params[2] === 'nueva') $admin->formHabitacion();
                    elseif ($params[2] === 'editar') { $_GET['id'] = $params[3] ?? 0; $admin->formHabitacion(); }
                    elseif ($params[2] === 'eliminar') { $_GET['id'] = $params[3] ?? 0; $admin->eliminarHabitacion(); }
                } else {
                    $admin->listaHabitaciones();
                }
                break;
            case 'tipos':
                if (isset($params[2])) {
                    if ($params[2] === 'nuevo') $admin->formTipo();
                    elseif ($params[2] === 'editar') { $_GET['id'] = $params[3] ?? 0; $admin->formTipo(); }
                    elseif ($params[2] === 'eliminar') { $_GET['id'] = $params[3] ?? 0; $admin->eliminarTipo(); }
                } else {
                    $admin->listaTipos();
                }
                break;
            case 'hoteles':
                if (isset($params[2])) {
                    if ($params[2] === 'nuevo') $admin->formHotel();
                    elseif ($params[2] === 'editar') { $_GET['id'] = $params[3] ?? 0; $admin->formHotel(); }
                    elseif ($params[2] === 'eliminar') { $_GET['id'] = $params[3] ?? 0; $admin->eliminarHotel(); }
                } else {
                    $admin->listaHoteles();
                }
                break;
            default:
                echo '404 - Página no encontrada';
                break;
        }
        break;

    default:
        echo '404 - Página no encontrada';
        break;
}

