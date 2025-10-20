<?php
require __DIR__ . '/../models/HabitacionModel.php';
require __DIR__ . '/../models/TipoHabitacionModel.php';

class HabitacionController {
    private $pdo;
    private $model;
    private $tipoModel;

    public function __construct($pdo){
        $this->pdo = $pdo;
        $this->model = new HabitacionModel($pdo);
        $this->tipoModel = new TipoHabitacionModel($pdo);
    }

    public function listar(){
        $habitaciones = $this->model->getAll();
        require __DIR__ . '/../view/public/listadoHabitaciones.phtml';
    }

    public function detalle(){
        $id = $_GET['id'] ?? 0;
        $habitacion = $this->model->getById($id);
        require __DIR__ . '/../view/public/detalleHabitacion.phtml';
    }

    public function porTipo(){
        $id_tipo = $_GET['id_tipo'] ?? 0;
        $habitaciones = $this->model->getByTipo($id_tipo);
        $tipo = $this->tipoModel->getById($id_tipo);
        require __DIR__ . '/../view/public/habitacionesPorTipo.phtml';
    }
}
?>