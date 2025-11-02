<?php
require_once __DIR__ . '/model.php';
class UsuarioModel extends model
{
    public function getAll()
    {
        $stmt = $this->pdo->query('SELECT * FROM usuario');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getById(int $id)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM usuario WHERE id_usuario = :id');
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }
    public function getByEmail(string $email)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM usuario WHERE email = :email');
        $stmt->execute([':email' => $email]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }
    public function create(array $data)
    {
        $password = $data['password'] ?? '';
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $sql = 'INSERT INTO usuario (nombre, apellido, email, telefono, password) VALUES (:nombre, :apellido, :email, :telefono, :password)';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':nombre' => $data['nombre'] ?? null,
            ':apellido' => $data['apellido'] ?? null,
            ':email' => $data['email'] ?? null,
            ':telefono' => $data['telefono'] ?? null,
            ':password' => $hash,
        ]);
        return (int)$this->pdo->lastInsertId();
    }
    public function update(int $id, array $data)
    {
        $params = [
            ':nombre' => $data['nombre'] ?? null,
            ':apellido' => $data['apellido'] ?? null,
            ':email' => $data['email'] ?? null,
            ':telefono' => $data['telefono'] ?? null,
            ':id' => $id,
        ];
        $sql = 'UPDATE usuario SET nombre = :nombre, apellido = :apellido, email = :email, telefono = :telefono';
        if (!empty($data['password'])) {
            $sql .= ', password = :password';
            $params[':password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }
        $sql .= ' WHERE id_usuario = :id';
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($params);
    }
    public function delete(int $id)
    {
        $stmt = $this->pdo->prepare('DELETE FROM usuario WHERE id_usuario = :id');
        return $stmt->execute([':id' => $id]);
    }
}
