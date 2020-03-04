<?php

if (php_sapi_name() == "cli") {
    include_once dirname(dirname(__DIR__)).'/config.php';
}

class DBC
{

    public $connection = null;
    public $query = null;
    public static $instance = [];

    public function __construct($define = [])
    {

        if ($this->connection == null) {
            $username = isset($define['DB_USERNAME']) ? $define['DB_USERNAME'] : DB_USERNAME;
            $password = isset($define['DB_PASSWORD']) ? $define['DB_PASSWORD'] : DB_PASSWORD;
            $host = isset($define['DB_HOSTNAME']) ? $define['DB_HOSTNAME'] : DB_HOSTNAME;
            $db = isset($define['DB_DATABASE']) ? $define['DB_DATABASE'] : DB_DATABASE;
            $options = array(
                PDO::MYSQL_ATTR_FOUND_ROWS => true,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_EMULATE_PREPARES => false
            );

            $this->connection = new \PDO("mysql:dbname=$db;host=$host", $username, $password, $options);
            $this->connection->exec("set names utf8");
        }
        return $this;
    }

    public static function gi($c)
    {
        $db = isset($c['DB_DATABASE']) ? $c['DB_DATABASE'] : DB_DATABASE;

        if (!isset(self::$instance[$db])) {
            self::$instance[$db] = new DBC($c);
        }

        return self::$instance[$db];
    }

    public function getLastid()
    {
        return $this->connection->lastInsertId();
    }

    public function beginTransaction()
    {
        $this->connection->beginTransaction();
    }

    public function rollBack()
    {
        $this->connection->rollBack();
    }

    public function commit()
    {
        $this->connection->commit();
    }



    public function query($sql, $values = [])
    {

        try {
            $this->query = $this->connection->prepare($sql);
            if (is_array($values)) {
                $this->query->execute(array_values($values));
            } else {
                $this->query->execute();
            }
            return $this;
        } catch (PDOException $e) {
            echo $sql;
            echo "Error: " . $e->getMessage();
        }
        return false;
    }



    public function select($sql, $where = [])
    {
        $this->query = $this->connection->prepare($sql);
        $this->query->execute(array_values($where));
        return $this;
    }

    public function first($column = '*')
    {
        $all = $this->query->fetch(PDO::FETCH_ASSOC);

        if (is_array($column) || ($column != '*' && is_string($column))) {

            if (is_string($column)) {
                $column = explode(',', $column);
            }
            if (is_array($all)) {
                return array_filter($all, function ($key) use ($column) {
                    return in_array($key, $column);
                }, ARRAY_FILTER_USE_KEY);
            }
        }

        return $all;
    }

    public function get()
    {
        return $this->query->fetchall(PDO::FETCH_ASSOC);
    }

    public function count()
    {
        return $this->query->rowCount();
    }
}

function db($d = [])
{
    return DBC::gi($d);
}
