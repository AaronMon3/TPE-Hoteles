<?php
require_once __DIR__ . '/model.php';
class HabitacionModel extends model
{
    public function getAll()
    {
        $stmt = $this->pdo->query('SELECT * FROM habitacion');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getById(int $id)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM habitacion WHERE id_habitacion = :id');
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }
    public function getByTipo(int $id_tipo)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM habitacion WHERE id_tipo = :id_tipo');
        $stmt->execute([':id_tipo' => $id_tipo]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function create(array $data)
    {
        $sql = 'INSERT INTO habitacion (numero, precio, id_hotel, id_tipo, imagen_url) VALUES (:numero, :precio, :id_hotel, :id_tipo, :imagen_url)';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':numero' => $data['numero'] ?? null,
            ':precio' => $data['precio'] ?? 0,
            ':id_hotel' => $data['id_hotel'] ?? null,
            ':id_tipo' => $data['id_tipo'] ?? null,
            ':imagen_url' => $data['imagen_url'] ?? null,
        ]);
        return (int)$this->pdo->lastInsertId();
    }
    public function update(int $id, array $data)
    {
        $sql = 'UPDATE habitacion SET numero = :numero, precio = :precio, id_hotel = :id_hotel, id_tipo = :id_tipo, imagen_url = :imagen_url WHERE id_habitacion = :id';
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':numero' => $data['numero'] ?? null,
            ':precio' => $data['precio'] ?? 0,
            ':id_hotel' => $data['id_hotel'] ?? null,
            ':id_tipo' => $data['id_tipo'] ?? null,
            ':imagen_url' => $data['imagen_url'] ?? null,
            ':id' => $id,
        ]);
    }
    public function delete(int $id)
    {
        $stmt = $this->pdo->prepare('DELETE FROM habitacion WHERE id_habitacion = :id');
        return $stmt->execute([':id' => $id]);
    }
}
