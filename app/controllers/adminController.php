<?php
require_once __DIR__ . '/../models/adminModel.php';
require_once __DIR__ . '/../models/habitacionModel.php';
require_once __DIR__ . '/../models/tipoHabitacionModel.php';
require_once __DIR__ . '/../models/hotelModel.php';
require_once __DIR__ . '/../views/admin.view.php';
class AdminController
{
    private $adminModel;
    private $habitacionModel;
    private $tipoModel;
    private $hotelModel;
    public function __construct()
    {
    require_once __DIR__ . '/../models/Model.php';
    $pdo = model::createPDO();
    $this->adminModel = new AdminModel($pdo);
    $this->habitacionModel = new HabitacionModel($pdo);
    $this->tipoModel = new TipoHabitacionModel($pdo);
    $this->hotelModel = new HotelModel($pdo);
    }
    private function isLogged()
    {
        return !empty($_SESSION['admin']);
    }
    public function login()
    {
        if ($this->isLogged()) {
            header('Location: ' . url('admin/dashboard'));
            exit;
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';
            if ($this->adminModel->checkCredentials($username, $password)) {
                $_SESSION['admin'] = ['username' => $username];
                header('Location: ' . url('admin/dashboard'));
                exit;
            } else {
                $error = 'Usuario o contraseña inválidos';
            }
        }
        (new AdminView())->loginView($error ?? null);
    }
    public function logout()
    {
        unset($_SESSION['admin']);
        session_destroy();
    header('Location: ' . url('admin/login'));
        exit;
    }
    public function dashboard()
    {
        if (!$this->isLogged()) {
            header('Location: ' . url('admin/login'));
            exit;
        }
        (new AdminView())->dashboardView();
    }
    public function listaHabitaciones()
    {
        if (!$this->isLogged()) {
            header('Location: ' . url('admin/login'));
            exit;
        }
    $habitaciones = $this->habitacionModel->getAll();
        $updated = [];
        foreach ($habitaciones as $h) {
            $t = $this->tipoModel->getById((int)$h['id_tipo']);
            $h['tipo_nombre'] = $t ? $t['nombre'] : '';
            $updated[] = $h;
        }
        $habitaciones = $updated;
        (new AdminView())->listaHabitacionesView($habitaciones);
    }
    public function formHabitacion()
    {
        if (!$this->isLogged()) {
            header('Location: ' . url('admin/login'));
            exit;
        }
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $habitacion = $id ? $this->habitacionModel->getById($id) : null;
        $tipos = $this->tipoModel->getAll();
        $hoteles = $this->hotelModel->getAll();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'numero' => $_POST['numero'] ?? null,
                'precio' => $_POST['precio'] ?? 0,
                'id_hotel' => $_POST['id_hotel'] ?? null,
                'id_tipo' => $_POST['id_tipo'] ?? null,
                'imagen_url' => $_POST['imagen_url'] ?? null,
            ];
            if ($id) {
                $this->habitacionModel->update($id, $data);
            } else {
                $this->habitacionModel->create($data);
            }
            header('Location: ' . url('admin/habitaciones'));
            exit;
        }
        (new AdminView())->formHabitacionView($habitacion, $tipos, $hoteles);
    }
    public function eliminarHabitacion()
    {
        if (!$this->isLogged()) {
            header('Location: ' . url('admin/login'));
            exit;
        }
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $habitacion = $this->habitacionModel->getById($id);
        if ($habitacion) {
            $this->habitacionModel->delete($id);
        }
        header('Location: ' . url('admin/habitaciones'));
        exit;
    }
    public function listaTipos()
    {
        if (!$this->isLogged()) {
            header('Location: ' . url('admin/login'));
            exit;
        }
        $tipos = $this->tipoModel->getAll();
        (new AdminView())->listaTiposView($tipos);
    }
    public function formTipo()
    {
        if (!$this->isLogged()) {
            header('Location: ' . url('admin/login'));
            exit;
        }
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $tipo = $id ? $this->tipoModel->getById($id) : null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'nombre' => $_POST['nombre'] ?? null,
                'descripcion' => $_POST['descripcion'] ?? null,
            ];
            if ($id) {
                $this->tipoModel->update($id, $data);
            } else {
                $this->tipoModel->create($data);
            }
            header('Location: ' . url('admin/tipos'));
            exit;
        }
        (new AdminView())->formTipoView($tipo);
    }
    public function eliminarTipo()
    {
        if (!$this->isLogged()) {
            header('Location: ' . url('admin/login'));
            exit;
        }
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $tipo = $this->tipoModel->getById($id);
        if ($tipo) {
            $this->tipoModel->delete($id);
        }
        header('Location: ' . url('admin/tipos'));
        exit;
    }
    public function listaHoteles()
    {
        if (!$this->isLogged()) {
            header('Location: ' . url('admin/login'));
            exit;
        }
        $hoteles = $this->hotelModel->getAll();
        (new AdminView())->listaHotelesView($hoteles);
    }
    public function formHotel()
    {
        if (!$this->isLogged()) {
            header('Location: ' . url('admin/login'));
            exit;
        }
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $hotel = $id ? $this->hotelModel->getById($id) : null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'nombre' => $_POST['nombre'] ?? null,
                'direccion' => $_POST['direccion'] ?? null,
                'ciudad' => $_POST['ciudad'] ?? null,
                'telefono' => $_POST['telefono'] ?? null,
                'email' => $_POST['email'] ?? null,
            ];
            try {
                if ($id) {
                    $this->hotelModel->update($id, $data);
                } else {
                    $this->hotelModel->create($data);
                }
                header('Location: ' . url('admin/hoteles'));
                exit;
            } catch (\Throwable $e) {
                // capture error and show it in the form instead of blank page
                $error = $e->getMessage();
            }
        }
        (new AdminView())->formHotelView($hotel, $error ?? null);
    }
    public function eliminarHotel()
    {
        if (!$this->isLogged()) {
            header('Location: ' . url('admin/login'));
            exit;
        }
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $hotel = $this->hotelModel->getById($id);
        if ($hotel) {
            $this->hotelModel->delete($id);
        }
        header('Location: ' . url('admin/hoteles'));
        exit;
    }

}
