<?php
require_once __DIR__ . '/model.php';
class AdminModel extends model
{
    public function getById(int $id)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM administrador WHERE id_admin = :id');
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }
    public function getByUsername(string $username)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM administrador WHERE username = :username');
        $stmt->execute([':username' => $username]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }
    public function checkCredentials(string $username, string $password)
    {
        $admin = $this->getByUsername($username);
        if (!$admin) return false;
        $stored = $admin['password'];
        $info = password_get_info($stored);
        if (!empty($info['algo']) && $info['algo'] !== 0) {
            return password_verify($password, $stored);
        }
        if (hash_equals((string)$stored, (string)$password)) {
            try {
                $newHash = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $this->pdo->prepare('UPDATE administrador SET password = :pw WHERE id_admin = :id');
                $stmt->execute([':pw' => $newHash, ':id' => (int)$admin['id_admin']]);
            } catch (\Throwable $e) {
            }
            return true;
        }
        return false;
    }
    public function create(array $data)
    {
        $password = $data['password'] ?? '';
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $sql = 'INSERT INTO administrador (username, password, email, nombre, apellido) VALUES (:username, :password, :email, :nombre, :apellido)';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':username' => $data['username'] ?? null,
            ':password' => $hash,
            ':email' => $data['email'] ?? null,
            ':nombre' => $data['nombre'] ?? null,
            ':apellido' => $data['apellido'] ?? null,
        ]);
        return (int)$this->pdo->lastInsertId();
    }
    public function delete(int $id)
    {
        $stmt = $this->pdo->prepare('DELETE FROM administrador WHERE id_admin = :id');
        return $stmt->execute([':id' => $id]);
    }
}
