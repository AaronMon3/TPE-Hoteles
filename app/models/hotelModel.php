<?php
require_once __DIR__ . '/model.php';
class HotelModel extends model
{
    public function getAll()
    {
        $stmt = $this->pdo->query('SELECT * FROM hotel');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getById(int $id)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM hotel WHERE id_hotel = :id');
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }
    public function create(array $data)
    {
        $sql = 'INSERT INTO hotel (nombre, direccion, ciudad, telefono, email) VALUES (:nombre, :direccion, :ciudad, :telefono, :email)';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':nombre' => $data['nombre'] ?? null,
            ':direccion' => $data['direccion'] ?? null,
            ':ciudad' => $data['ciudad'] ?? null,
            ':telefono' => $data['telefono'] ?? null,
            ':email' => $data['email'] ?? null,
        ]);
        return (int)$this->pdo->lastInsertId();
    }
    public function update(int $id, array $data)
    {
        $sql = 'UPDATE hotel SET nombre = :nombre, direccion = :direccion, ciudad = :ciudad, telefono = :telefono, email = :email WHERE id_hotel = :id';
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':nombre' => $data['nombre'] ?? null,
            ':direccion' => $data['direccion'] ?? null,
            ':ciudad' => $data['ciudad'] ?? null,
            ':telefono' => $data['telefono'] ?? null,
            ':email' => $data['email'] ?? null,
            ':id' => $id,
        ]);
    }
    public function delete(int $id)
    {
        $stmtHabitaciones = $this->pdo->prepare('DELETE FROM habitacion WHERE id_hotel = :id');
        $stmtHabitaciones->execute([':id' => $id]);
        
        
        $stmt = $this->pdo->prepare('DELETE FROM hotel WHERE id_hotel = :id');
        return $stmt->execute([':id' => $id]);
    }
}

