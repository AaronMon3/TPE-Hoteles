<?php
require_once __DIR__ . '/model.php';
class ReservaModel extends model
{
    public function getAll()
    {
        $stmt = $this->pdo->query('SELECT * FROM reserva');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getById(int $id)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM reserva WHERE id_reserva = :id');
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }
    public function create(array $data)
    {
        $sql = 'INSERT INTO reserva (id_usuario, id_habitacion, fecha_inicio, fecha_fin) VALUES (:id_usuario, :id_habitacion, :fecha_inicio, :fecha_fin)';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':id_usuario' => $data['id_usuario'] ?? null,
            ':id_habitacion' => $data['id_habitacion'] ?? null,
            ':fecha_inicio' => $data['fecha_inicio'] ?? null,
            ':fecha_fin' => $data['fecha_fin'] ?? null,
        ]);
        return (int)$this->pdo->lastInsertId();
    }
    public function update(int $id, array $data)
    {
        $sql = 'UPDATE reserva SET id_usuario = :id_usuario, id_habitacion = :id_habitacion, fecha_inicio = :fecha_inicio, fecha_fin = :fecha_fin WHERE id_reserva = :id';
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':id_usuario' => $data['id_usuario'] ?? null,
            ':id_habitacion' => $data['id_habitacion'] ?? null,
            ':fecha_inicio' => $data['fecha_inicio'] ?? null,
            ':fecha_fin' => $data['fecha_fin'] ?? null,
            ':id' => $id,
        ]);
    }
    public function delete(int $id)
    {
        $stmt = $this->pdo->prepare('DELETE FROM reserva WHERE id_reserva = :id');
        return $stmt->execute([':id' => $id]);
    }
}
