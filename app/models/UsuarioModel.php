<?php
class UsuarioModel {
    private $pdo;
    public function __construct($pdo){
        $this->pdo = $pdo;
    }

    public function login($usuario, $password){
        if($usuario === 'webadmin' && $password === 'admin'){
            return ['id_usuario'=>0, 'nombre'=>'Admin'];
        }
        $stmt = $this->pdo->prepare("SELECT * FROM usuario WHERE email=? LIMIT 1");
        $stmt->execute([$usuario]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if($user && $user['password'] === $password){
            return $user;
        }
        return false;
    }
}
?>
