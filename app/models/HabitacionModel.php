<?php
class HabitacionModel {
    private $pdo;
    public function __construct($pdo){
        $this->pdo = $pdo;
    }

    public function getAll(){
        $stmt = $this->pdo->query("SELECT h.*, t.nombre AS tipo_nombre FROM habitacion h 
                                   JOIN tipohabitacion t ON h.id_tipo = t.id_tipo");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id){
        $stmt = $this->pdo->prepare("SELECT h.*, t.nombre AS tipo_nombre FROM habitacion h 
                                     JOIN tipohabitacion t ON h.id_tipo = t.id_tipo
                                     WHERE id_habitacion = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getByTipo($id_tipo){
        $stmt = $this->pdo->prepare("SELECT h.*, t.nombre AS tipo_nombre FROM habitacion h 
                                     JOIN tipohabitacion t ON h.id_tipo = t.id_tipo
                                     WHERE h.id_tipo = ?");
        $stmt->execute([$id_tipo]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
