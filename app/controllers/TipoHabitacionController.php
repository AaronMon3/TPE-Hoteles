<?php
require_once __DIR__ . '/../models/TipoHabitacionModel.php';

class TipoHabitacionController {
    private $pdo;
    private $model;

    public function __construct($pdo){
        $this->pdo = $pdo;
        $this->model = new TipoHabitacionModel($pdo);
    }

        public function listar(){
        // Obtener todos los tipos desde el modelo
        $tipos = $this->model->getAll();
        // Mostrar la vista
        require __DIR__ . '/../view/public/listadoTipos.phtml';
    }
}
?>

