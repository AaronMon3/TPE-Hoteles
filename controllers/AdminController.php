<?php
require __DIR__ . '/../models/UsuarioModel.php';
require __DIR__ . '/../models/HabitacionModel.php';
require __DIR__ . '/../models/TipoHabitacionModel.php';

class AdminController {
    private $pdo;
    private $usuarioModel;
    private $habitacionModel;
    private $tipoModel;

    public function __construct($pdo) {
        // Quita session_start() de aquí (ya está en index.php)
        $this->pdo = $pdo;
        $this->usuarioModel = new UsuarioModel($pdo);
        $this->habitacionModel = new HabitacionModel($pdo);
        $this->tipoModel = new TipoHabitacionModel($pdo);
    }

    private function checkLogin() {
        if (!isset($_SESSION['admin'])) {
            header('Location: /hoteles/admin/login');  // Cambia a URL semántica
            exit;
        }
    }

    public function login() {
        $error = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario = $_POST['usuario'] ?? '';
            $password = $_POST['password'] ?? '';
            // Simplifica a credenciales fijas (como la consigna)
            if ($usuario == 'webadmin' && $password == 'admin') {
                $_SESSION['admin'] = true;  // Solo marca como logueado
                header('Location: /hoteles/admin/dashboard');  // URL semántica
                exit;
            } else {
                $error = "Usuario o contraseña incorrectos";
            }
        }
        require __DIR__ . '/../view/admin/login.phtml';
    }

    public function logout() {
        session_destroy();
        header('Location: /hoteles/admin/login');  // URL semántica
        exit;
    }

    public function dashboard() {
        $this->checkLogin();
        require __DIR__ . '/../view/admin/dashboard.phtml';
    }

    // --- ABM Habitaciones ---
    public function listaHabitaciones() {
        $this->checkLogin();
        $habitaciones = $this->habitacionModel->getAll();
        require __DIR__ . '/../view/admin/listaHabitaciones.phtml';
    }

    public function formHabitacion() {
        $this->checkLogin();
        $id = $_GET['id'] ?? null;
        $tipos = $this->tipoModel->getAll();
        $habitacion = $id ? $this->habitacionModel->getById($id) : null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $numero = $_POST['numero'];
            $precio = $_POST['precio'];
            $id_tipo = $_POST['id_tipo'];
            // Manejo de imagen 
            $imagen_url = $habitacion['imagen_url'] ?? null;
            $previous_image = $imagen_url;
            if (isset($_FILES['imagen_file']) && $_FILES['imagen_file']['error'] !== UPLOAD_ERR_NO_FILE) {
                $file = $_FILES['imagen_file'];
                $maxSize = 2 * 1024 * 1024;
                $allowed = ['image/jpeg', 'image/png', 'image/gif'];
                if ($file['error'] === UPLOAD_ERR_OK && $file['size'] <= $maxSize) {
                    $finfo = finfo_open(FILEINFO_MIME_TYPE);
                    $mime = finfo_file($finfo, $file['tmp_name']);
                    finfo_close($finfo);
                    if (in_array($mime, $allowed)) {
                        $ext = '';
                        switch ($mime) {
                            case 'image/jpeg': $ext = 'jpg'; break;
                            case 'image/png': $ext = 'png'; break;
                            case 'image/gif': $ext = 'gif'; break;
                        }
                        $uploadsDir = __DIR__ . '/../view/public/uploads';
                        if (!is_dir($uploadsDir)) mkdir($uploadsDir, 0755, true);
                        $filename = uniqid('hab_') . '.' . $ext;
                        $dest = $uploadsDir . '/' . $filename;
                                                if (move_uploaded_file($file['tmp_name'], $dest)) {
                            $imagen_url = '/hoteles/view/public/uploads/' . $filename;
                            if ($previous_image && strpos($previous_image, '/hoteles/view/public/uploads/') === 0) {
                                $prevPath = __DIR__ . '/../' . str_replace('/hoteles/', '', $previous_image);
                                if (is_file($prevPath)) @unlink($prevPath);
                            }
                        }
                    }
                }
            }
            if (empty($imagen_url) && !empty($_POST['imagen_url_text'])) {
                $url = trim($_POST['imagen_url_text']);
                if (filter_var($url, FILTER_VALIDATE_URL)) {
                    if ($previous_image && strpos($previous_image, '/hoteles/view/public/uploads/') === 0) {
                        $prevPath = __DIR__ . '/../' . str_replace('/hoteles/', '', $previous_image);
                        if (is_file($prevPath)) @unlink($prevPath);
                    }
                    $imagen_url = $url;
                }
            }

            if ($id) { // Editar
                $stmt = $this->pdo->prepare("UPDATE habitacion SET numero=?, precio=?, id_tipo=?, imagen_url=? WHERE id_habitacion=?");
                $stmt->execute([$numero, $precio, $id_tipo, $imagen_url, $id]);
            } else { // Agregar
                $stmt = $this->pdo->prepare("INSERT INTO habitacion (numero, precio, id_tipo, imagen_url, id_hotel) VALUES (?,?,?,?,1)");
                $stmt->execute([$numero, $precio, $id_tipo, $imagen_url]);
            }
            header('Location: /hoteles/admin/habitaciones');  
            exit;
        }
        require __DIR__ . '/../view/admin/formHabitacion.phtml';
    }

    public function eliminarHabitacion() {
        $this->checkLogin();
        $id = $_GET['id'] ?? 0;
        $stmt = $this->pdo->prepare("DELETE FROM habitacion WHERE id_habitacion=?");
        $stmt->execute([$id]);
        header('Location: /hoteles/admin/habitaciones');
        exit;
    }

        // --- ABM Tipos ---
    public function listaTipos() {
        $this->checkLogin();
        $tipos = $this->tipoModel->getAll();
        require __DIR__ . '/../view/admin/listaTipo.phtml';
    }

        public function formTipo() {
        $this->checkLogin();
        $id = $_GET['id'] ?? null;
        $tipo = $id ? $this->tipoModel->getById($id) : null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = $_POST['nombre'];
            $descripcion = $_POST['descripcion'];
            // Manejo de imagen
            $imagen_url = $tipo['imagen_url'] ?? null;
            $previous_image = $imagen_url;
            
            // Subida de archivo
            if (isset($_FILES['imagen_file']) && $_FILES['imagen_file']['error'] !== UPLOAD_ERR_NO_FILE) {
                $file = $_FILES['imagen_file'];
                $maxSize = 2 * 1024 * 1024;
                $allowed = ['image/jpeg', 'image/png', 'image/gif'];
                if ($file['error'] === UPLOAD_ERR_OK && $file['size'] <= $maxSize) {
                    $finfo = finfo_open(FILEINFO_MIME_TYPE);
                    $mime = finfo_file($finfo, $file['tmp_name']);
                    finfo_close($finfo);
                    if (in_array($mime, $allowed)) {
                        $ext = '';
                        switch ($mime) {
                            case 'image/jpeg': $ext = 'jpg'; break;
                            case 'image/png': $ext = 'png'; break;
                            case 'image/gif': $ext = 'gif'; break;
                        }
                        $uploadsDir = __DIR__ . '/../view/public/uploads';
                        if (!is_dir($uploadsDir)) mkdir($uploadsDir, 0755, true);
                        $filename = uniqid('tipo_') . '.' . $ext;
                        $dest = $uploadsDir . '/' . $filename;
                        if (move_uploaded_file($file['tmp_name'], $dest)) {
                            $imagen_url = '/hoteles/view/public/uploads/' . $filename;
                            if ($previous_image && strpos($previous_image, '/hoteles/view/public/uploads/') === 0) {
                                $prevPath = __DIR__ . '/../' . str_replace('/hoteles/', '', $previous_image);
                                if (is_file($prevPath)) @unlink($prevPath);
                            }
                        }
                    }
                }
            }
            
            // URL externa
            if (empty($imagen_url) && !empty($_POST['imagen_url_text'])) {
                $url = trim($_POST['imagen_url_text']);
                if (filter_var($url, FILTER_VALIDATE_URL)) {
                    if ($previous_image && strpos($previous_image, '/hoteles/view/public/uploads/') === 0) {
                        $prevPath = __DIR__ . '/../' . str_replace('/hoteles/', '', $previous_image);
                        if (is_file($prevPath)) @unlink($prevPath);
                    }
                    $imagen_url = $url;
                }
            }

            if ($id) { // Editar
                $stmt = $this->pdo->prepare("UPDATE tipohabitacion SET nombre=?, descripcion=?, imagen_url=? WHERE id_tipo=?");
                $stmt->execute([$nombre, $descripcion, $imagen_url, $id]);
            } else { // Agregar
                $stmt = $this->pdo->prepare("INSERT INTO tipohabitacion (nombre, descripcion, imagen_url) VALUES (?,?,?)");
                $stmt->execute([$nombre, $descripcion, $imagen_url]);
            }
            header('Location: /hoteles/admin/tipos');
            exit;
        }
        require __DIR__ . '/../view/admin/formTipo.phtml';
    }

        public function eliminarTipo() {
        $this->checkLogin();
        $id = $_GET['id'] ?? 0;
        $stmt = $this->pdo->prepare("DELETE FROM tipohabitacion WHERE id_tipo=?");
        $stmt->execute([$id]);
        header('Location: /hoteles/admin/tipos');  // URL semántica
        exit;
    }
}
?>