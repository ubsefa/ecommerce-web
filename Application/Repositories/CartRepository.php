<?php

namespace Repositories;

class CartRepository extends \Libraries\Database
{
    public function getById($id): ?\Models\CartModel
    {
        $this->query("SELECT * FROM \"carts\" WHERE \"id\"=:id");
        $this->bindValue(":id", $id);
        $row = $this->fetch();
        if ($this->rowCount() > 0) {
            $cart = new \Models\CartModel;
            foreach ($row as $property => $value) {
                $cart->$property = $value;
            }
            return $cart;
        } else {
            return null;
        }
    }

    public function getList($where, $orderBy, $limit, $offset, $parameters = array())
    {
        $this->query("SELECT * FROM \"carts\" {$where} {$orderBy} {$limit} {$offset}", $parameters);
        $rows = $this->fetchAll();
        $carts = array();
        foreach ($rows as $row) {
            $cart = array();
            foreach ($row as $property => $value) {
                $cart[$property] = $value;
            }
            array_push($carts, (object) $cart);
        }
        return $carts;
    }

    public function add(\Models\CartModel $cart): ?\Models\CartModel
    {
        $this->query("SELECT * FROM \"carts\" WHERE \"order\"=:order AND \"product\"=:product");
        $this->bindValue(":order", $cart->order);
        $this->bindValue(":product", $cart->product);
        $row = $this->fetch();
        if ($this->rowCount() == 0) {
            $columns = array();
            $values = array();
            $parameters = array();
            foreach ($cart as $property => $value) {
                if ($property != "id" && !is_null($value)) {
                    array_push($columns, "\"{$property}\"");
                    array_push($values, ":{$property}");
                    array_push($parameters, array("name" => ":{$property}", "value" => $value));
                }
            }
            $columns = implode(", ", $columns);
            $values = implode(", ", $values);
            $this->query("INSERT INTO \"carts\" ({$columns}) VALUES ({$values})", $parameters);
            $this->execute();
            return $this->getById($this->lastInsertId());
        } else {
            return null;
        }
    }

    public function update(\Models\CartModel $cart): ?\Models\CartModel
    {
        $this->query("SELECT * FROM \"carts\" WHERE \"id\"!=:id AND \"order\"=:order AND \"product\"=:product");
        $this->bindValue(":id", $cart->id);
        $this->bindValue(":order", $cart->order);
        $this->bindValue(":product", $cart->product);
        $row = $this->fetch();
        if ($this->rowCount() == 0) {
            $columns = array();
            $parameters = array();
            foreach ($cart as $property => $value) {
                if ($property != "id" && !is_null($value)) {
                    array_push($columns, "\"{$property}\"=:{$property}");
                    array_push($parameters, array("name" => ":{$property}", "value" => $value));
                }
            }
            $columns = implode(", ", $columns);
            $this->query("UPDATE \"carts\" SET {$columns} WHERE \"id\"=:id", $parameters);
            $this->bindValue(":id", $cart->id);
            foreach ($parameters as $parameter) {
                $this->bindValue($parameter["name"], $parameter["value"]);
            }
            $this->execute();
        } else {
            return null;
        }
    }

    public function delete(\Models\CartModel $cart)
    {
        $this->query("DELETE FROM \"carts\"  WHERE \"id\"=:id");
        $this->bindValue(":id", $cart->id);
        $row = $this->execute();
        if ($this->rowCount() == 0) {
            return true;
        } else {
            return null;
        }
    }
}
