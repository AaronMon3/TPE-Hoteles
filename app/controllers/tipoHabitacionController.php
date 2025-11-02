<?php
require_once __DIR__ . '/../models/tipoHabitacionModel.php';
require_once __DIR__ . '/../views/tipos.view.php';
class TipoHabitacionController
{
    private $tipoModel;
    public function __construct()
    {
    require_once __DIR__ . '/../models/Model.php';
    $pdo = model::createPDO();
    $this->tipoModel = new TipoHabitacionModel($pdo);
    }
    public function listar()
    {
        $tipos = $this->tipoModel->getAll();
        (new TiposView())->listView($tipos);
    }
}

