<?php

namespace Repositories;

class PairRepository extends \Libraries\Database
{
    public function getById($id): ?\Models\PairModel
    {
        $this->query("SELECT * FROM \"pairs\" WHERE \"id\"=:id");
        $this->bindValue(":id", $id);
        $row = $this->fetch();
        if ($this->rowCount() > 0) {
            $pair = new \Models\PairModel;
            foreach ($row as $property => $value) {
                $pair->$property = $value;
            }
            return $pair;
        } else {
            return null;
        }
    }

    public function getList($where, $orderBy, $limit, $offset, $parameters = array())
    {
        $this->query("SELECT * FROM \"pairs\" {$where} {$orderBy} {$limit} {$offset}", $parameters);
        $rows = $this->fetchAll();
        $pairs = array();
        foreach ($rows as $row) {
            $pair = array();
            foreach ($row as $property => $value) {
                $pair[$property] = $value;
            }
            array_push($pairs, (object) $pair);
        }
        return $pairs;
    }

    public function add(\Models\PairModel $pair): ?\Models\PairModel
    {
        $this->query("SELECT * FROM \"pairs\" WHERE LOWER(\"type\")=:type AND LOWER(\"object\")=:object AND \"key\"=:key AND LOWER(\"value\")=:value");
        $this->bindValue(":type", strtolower($pair->type));
        $this->bindValue(":object", strtolower($pair->object));
        $this->bindValue(":key", $pair->key);
        $this->bindValue(":value", strtolower($pair->value));
        $row = $this->fetch();
        if ($this->rowCount() == 0) {
            $columns = array();
            $values = array();
            $parameters = array();
            foreach ($pair as $property => $value) {
                if ($property != "id" && !is_null($value)) {
                    array_push($columns, "\"{$property}\"");
                    array_push($values, ":{$property}");
                    array_push($parameters, array("name" => ":{$property}", "value" => $value));
                }
            }
            $columns = implode(", ", $columns);
            $values = implode(", ", $values);
            $this->query("INSERT INTO \"pairs\" ({$columns}) VALUES ({$values})", $parameters);
            $this->execute();
            return $this->getById($this->lastInsertId());
        } else {
            return null;
        }
    }

    public function update(\Models\PairModel $pair): ?\Models\PairModel
    {
        $this->query("SELECT * FROM \"pairs\" WHERE \"id\"!=:id AND LOWER(\"type\")=:type AND LOWER(\"object\")=:object AND \"key\"=:key AND LOWER(\"value\")=:value");
        $this->bindValue(":id", $pair->id);
        $this->bindValue(":type", strtolower($pair->type));
        $this->bindValue(":object", strtolower($pair->object));
        $this->bindValue(":key", $pair->key);
        $this->bindValue(":value", strtolower($pair->value));
        $row = $this->fetch();
        if ($this->rowCount() == 0) {
            $columns = array();
            $parameters = array();
            foreach ($pair as $property => $value) {
                if ($property != "id" && !is_null($value)) {
                    array_push($columns, "\"{$property}\"=:{$property}");
                    array_push($parameters, array("name" => ":{$property}", "value" => $value));
                }
            }
            $columns = implode(", ", $columns);
            $this->query("UPDATE \"pairs\" SET {$columns} WHERE \"id\"=:id", $parameters);
            $this->bindValue(":id", $pair->id);
            foreach ($parameters as $parameter) {
                $this->bindValue($parameter["name"], $parameter["value"]);
            }
            $this->execute();
        } else {
            return null;
        }
    }

    public function delete(\Models\PairModel $pair)
    {
        $this->query("DELETE FROM \"pairs\"  WHERE \"id\"=:id");
        $this->bindValue(":id", $pair->id);
        $row = $this->execute();
        if ($this->rowCount() == 0) {
            return true;
        } else {
            return null;
        }
    }
}
