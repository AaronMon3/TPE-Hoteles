<?php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'hoteles_db');

$scriptName = $_SERVER['SCRIPT_NAME'];
$baseUrl = dirname($scriptName);
if ($baseUrl === '/') $baseUrl = ''; // Si está en la raíz, dejar vacío
define('BASE_URL', $baseUrl);

try {
    $pdo = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->query("SELECT 1 FROM tipohabitacion LIMIT 1");
} catch (PDOException $e) {
    $pdo = new PDO("mysql:host=".DB_HOST, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    if(file_exists('hoteles_db.sql')){
    $sql = file_get_contents('hoteles_db.sql');
        $pdo->exec($sql);
    } else {
        die("Archivo SQL no encontrado");
    }
    $pdo = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
?>
