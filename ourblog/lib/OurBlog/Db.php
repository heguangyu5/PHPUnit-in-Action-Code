<?php

class OurBlog_Db
{
    protected static $instance;

    protected $pdo;

    protected function __construct()
    {
        $this->pdo = new PDO(
            'mysql:host=' . getenv('DB_HOST') . ';port=' . getenv('DB_PORT') . ';dbname=' . getenv('DB_DATABASE') . ';charset=utf8',
            getenv('DB_USER'),
            getenv('DB_PASSWORD')
        );
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    private function __clone()
    {}

    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function fetchOne($sql, $bind = array())
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($bind);
        return $stmt->fetchColumn();
    }

    public function fetchRow($sql, $bind = array(), $style = PDO::FETCH_ASSOC)
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($bind);
        return $stmt->fetch($style);
    }

    public function fetchAll($sql, $bind = array(), $style = PDO::FETCH_ASSOC)
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($bind);
        return $stmt->fetchAll($style);
    }

    public function fetchCol($sql, $bind = array())
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($bind);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function insert($table, array $row)
    {
        if (!$row) {
            $this->pdo->exec("INSERT INTO `$table` VALUES (NULL)");
            return;
        }

        $columns = array();
        foreach (array_keys($row) as $key) {
            $columns[] = "`$key`";
        }
        $columns = implode(',', $columns);
        $placeholders = str_repeat('?,', count($row) - 1) . '?';
        $sql = "INSERT INTO `$table` ($columns) VALUES ($placeholders)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(array_values($row));
    }

    public function update($table, array $row, $where = '1')
    {
        if (!$row) {
            throw new Exception('update with empty row is not allowed!');
        }

        $updateData = array();
        foreach (array_keys($row) as $key) {
            $updateData[] = "`$key` = ?";
        }
        $updateData = implode(',', $updateData);
        $sql = "UPDATE `$table` SET $updateData WHERE $where";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(array_values($row));
    }

    public function __call($name, $args)
    {
        return call_user_func_array(array($this->pdo, $name), $args);
    }
}
