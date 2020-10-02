<?php

namespace Repositories;

class OrderRepository extends \Libraries\Database
{
    public function getById($id): ?\Models\OrderModel
    {
        $this->query("SELECT * FROM \"orders\" WHERE \"id\"=:id");
        $this->bindValue(":id", $id);
        $row = $this->fetch();
        if ($this->rowCount() > 0) {
            $order = new \Models\OrderModel;
            foreach ($row as $property => $value) {
                $order->$property = $value;
            }
            return $order;
        } else {
            return null;
        }
    }

    public function getList($where, $orderBy, $limit, $offset, $parameters = array())
    {
        $this->query("SELECT * FROM \"orders\" {$where} {$orderBy} {$limit} {$offset}", $parameters);
        $rows = $this->fetchAll();
        $orders = array();
        foreach ($rows as $row) {
            $order = array();
            foreach ($row as $property => $value) {
                $order[$property] = $value;
            }
            array_push($orders, (object) $order);
        }
        return $orders;
    }

    public function add(\Models\OrderModel $order): ?\Models\OrderModel
    {
        $this->query("SELECT * FROM \"orders\" WHERE \"creator\":creator AND \"active\"=:active");
        $this->bindValue(":creator", $order->creator);
        $this->bindValue(":active", "b1");
        $row = $this->fetch();
        if ($this->rowCount() == 0) {
            $columns = array();
            $values = array();
            $parameters = array();
            foreach ($order as $property => $value) {
                if ($property != "id" && !is_null($value)) {
                    array_push($columns, "\"{$property}\"");
                    array_push($values, ":{$property}");
                    array_push($parameters, array("name" => ":{$property}", "value" => $value));
                }
            }
            $columns = implode(", ", $columns);
            $values = implode(", ", $values);
            $this->query("INSERT INTO \"orders\" ({$columns}) VALUES ({$values})", $parameters);
            $this->execute();
            return $this->getById($this->lastInsertId());
        } else {
            return null;
        }
    }

    public function update(\Models\OrderModel $order): ?\Models\OrderModel
    {
        $this->query("SELECT * FROM \"orders\" WHERE \"id\"!=:id AND \"creator\":creator AND \"active\"=:active");
        $this->bindValue(":id", $order->id);
        $this->bindValue(":creator", $order->creator);
        $this->bindValue(":active", "b1");
        $row = $this->fetch();
        if ($this->rowCount() == 0) {
            $columns = array();
            $parameters = array();
            foreach ($order as $property => $value) {
                if ($property != "id" && !is_null($value)) {
                    array_push($columns, "\"{$property}\"=:{$property}");
                    array_push($parameters, array("name" => ":{$property}", "value" => $value));
                }
            }
            $columns = implode(", ", $columns);
            $this->query("UPDATE \"orders\" SET {$columns} WHERE \"id\"=:id", $parameters);
            $this->bindValue(":id", $order->id);
            foreach ($parameters as $parameter) {
                $this->bindValue($parameter["name"], $parameter["value"]);
            }
            $this->execute();
        } else {
            return null;
        }
    }

    public function delete(\Models\OrderModel $order)
    {
        $this->query("DELETE FROM \"orders\"  WHERE \"id\"=:id");
        $this->bindValue(":id", $order->id);
        $row = $this->execute();
        if ($this->rowCount() == 0) {
            return true;
        } else {
            return null;
        }
    }
}
