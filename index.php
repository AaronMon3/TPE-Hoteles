<?php
function redirect($path) {
    header("Location: " . BASE_URL . "/" . ltrim($path, "/"));
    exit;
}

function url($path = '') {
    return BASE_URL . '/' . ltrim($path, '/');
}

session_start();
require_once 'config.php';
require_once 'Router.php';
?>

