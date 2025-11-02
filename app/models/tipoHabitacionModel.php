<?php
require_once __DIR__ . '/model.php';
class TipoHabitacionModel extends model
{
    public function getAll()
    {
        $stmt = $this->pdo->query('SELECT * FROM tipohabitacion');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getById(int $id)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM tipohabitacion WHERE id_tipo = :id');
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }
    public function create(array $data)
    {
        $sql = 'INSERT INTO tipohabitacion (nombre, descripcion, imagen_url) VALUES (:nombre, :descripcion, :imagen_url)';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':nombre' => $data['nombre'] ?? null,
            ':descripcion' => $data['descripcion'] ?? null,
            ':imagen_url' => $data['imagen_url'] ?? null,
        ]);
        return (int)$this->pdo->lastInsertId();
    }
    public function update(int $id, array $data)
    {
        $sql = 'UPDATE tipohabitacion SET nombre = :nombre, descripcion = :descripcion, imagen_url = :imagen_url WHERE id_tipo = :id';
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':nombre' => $data['nombre'] ?? null,
            ':descripcion' => $data['descripcion'] ?? null,
            ':imagen_url' => $data['imagen_url'] ?? null,
            ':id' => $id,
        ]);
    }
    public function delete(int $id)
    {
        $stmt = $this->pdo->prepare('DELETE FROM tipohabitacion WHERE id_tipo = :id');
        return $stmt->execute([':id' => $id]);
    }
}
