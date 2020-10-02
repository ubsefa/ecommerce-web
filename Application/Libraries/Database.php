<?php

namespace Libraries;

use \PDO;

class Database
{
    private $dbh;
    private $stmt;

    public function __construct()
    {
        $this->dbh = new PDO(DATABASE_TYPE . ":host=" . DATABASE_HOST . "; dbname=" . DATABASE_DBNAME, DATABASE_USER, DATABASE_PASSWORD, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ, PDO::ATTR_EMULATE_PREPARES => true));
        $this->dbh->exec("SET NAMES 'UTF8';");
    }

    public function query($sql, $parameters = array())
    {
        $this->stmt = $this->dbh->prepare($sql);
        foreach ($parameters as $parameter) {
            $this->bindValue($parameter["name"], $parameter["value"], isset($parameter["type"]) ? $parameter["type"] : null);
        }
    }

    public function bindValue($parameter, $value, $type = null)
    {
        if (is_null($type)) {
            switch (true) {
                case is_int($value):
                    $type = PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = PDO::PARAM_NULL;
                    break;
                default:
                    $type = PDO::PARAM_STR;
            }
        }
        $this->stmt->bindValue($parameter, $value, $type);
    }

    public function execute()
    {
        return $this->stmt->execute();
    }

    public function fetch()
    {
        $this->execute();
        return $this->stmt->fetch();
    }

    public function fetchAll()
    {
        $this->execute();
        return $this->stmt->fetchAll();
    }

    public function fetchColumn()
    {
        $this->execute();
        return $this->stmt->fetchColumn();
    }

    public function rowCount()
    {
        return $this->stmt->rowCount();
    }

    public function lastInsertId()
    {
        return $this->dbh->lastInsertId();
    }

    public function __destruct()
    {
        $this->dbh = null;
    }
}
