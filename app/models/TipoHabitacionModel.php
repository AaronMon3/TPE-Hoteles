<?php
class TipoHabitacionModel {
    private $pdo;
    public function __construct($pdo){
        $this->pdo = $pdo;
    }

    public function getAll(){
        $stmt = $this->pdo->query("SELECT * FROM tipohabitacion");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id){
        $stmt = $this->pdo->prepare("SELECT * FROM tipohabitacion WHERE id_tipo = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
