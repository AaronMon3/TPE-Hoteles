<?php
require_once __DIR__ . '/../models/habitacionModel.php';
require_once __DIR__ . '/../models/tipoHabitacionModel.php';
require_once __DIR__ . '/../models/hotelModel.php';
require_once __DIR__ . '/../views/habitaciones.view.php';
class HabitacionController
{
    private $habitacionModel;
    private $tipoModel;
    private $hotelModel;
    public function __construct()
    {
    require_once __DIR__ . '/../models/Model.php';
    $pdo = model::createPDO();
    $this->habitacionModel = new HabitacionModel($pdo);
    $this->tipoModel = new TipoHabitacionModel($pdo);
    $this->hotelModel = new HotelModel($pdo);
    }
    public function listar()
    {
    $habitaciones = $this->habitacionModel->getAll();
    $tipos = $this->tipoModel->getAll();
    $hoteles = $this->hotelModel->getAll();
        $tipoMap = [];
        foreach ($tipos as $t) {
            $tipoMap[(int)$t['id_tipo']] = $t['nombre'];
        }
        $hotelMap = [];
        foreach ($hoteles as $ht) {
            $hotelMap[(int)$ht['id_hotel']] = $ht['nombre'];
        }
        $updated = [];
        foreach ($habitaciones as $h) {
            $h['tipo_nombre'] = $tipoMap[(int)($h['id_tipo'] ?? 0)] ?? '';
            $h['hotel_nombre'] = $hotelMap[(int)($h['id_hotel'] ?? 0)] ?? '';
            $updated[] = $h;
        }
        $habitaciones = $updated;
        (new HabitacionesView())->listView($habitaciones);
    }
    public function detalle()
    {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $habitacion = $this->habitacionModel->getById($id);
        if (!$habitacion) {
            http_response_code(404);
            (new HabitacionesView())->notFoundView();
            return;
        }
        $tipo = $this->tipoModel->getById((int)$habitacion['id_tipo']);
        $habitacion['tipo_nombre'] = $tipo ? $tipo['nombre'] : '';
        $hotel = $this->hotelModel->getById((int)$habitacion['id_hotel']);
        $habitacion['hotel_nombre'] = $hotel ? $hotel['nombre'] : '';
        (new HabitacionesView())->detalleView($habitacion);
    }
    public function porTipo()
    {
        $id_tipo = isset($_GET['id_tipo']) ? (int)$_GET['id_tipo'] : 0;
        $habitaciones = $this->habitacionModel->getByTipo($id_tipo);
        $tipo = $this->tipoModel->getById($id_tipo);
        $hoteles = $this->hotelModel->getAll();
        $hotelMap = [];
        foreach ($hoteles as $ht) {
            $hotelMap[(int)$ht['id_hotel']] = $ht['nombre'];
        }
        $updated = [];
        foreach ($habitaciones as $h) {
            $h['tipo_nombre'] = $tipo ? $tipo['nombre'] : '';
            $h['hotel_nombre'] = $hotelMap[(int)($h['id_hotel'] ?? 0)] ?? '';
            $updated[] = $h;
        }
        $habitaciones = $updated;
        (new HabitacionesView())->porTipoView($habitaciones, $tipo);
    }
}

