<?php
class model
{
    public static function createPDO() {
        require_once __DIR__ . '/../../config.php';
        $dsn = 'mysql:host=' . MYSQL_HOST . ';dbname=' . MYSQL_DB . ';charset=' . MYSQL_CHARSET;
        $pdo = new PDO($dsn, MYSQL_USER, MYSQL_PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    }
    protected $pdo;
    public function __construct(PDO $pdo)
    {
        if ($pdo instanceof PDO) {
            $this->pdo = $pdo;
            return;
        }
        require_once __DIR__ . '/../../config.php';
        $dsn = 'mysql:host=' . MYSQL_HOST . ';dbname=' . MYSQL_DB . ';charset=' . MYSQL_CHARSET;
        $this->pdo = new PDO($dsn, MYSQL_USER, MYSQL_PASS);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
}
