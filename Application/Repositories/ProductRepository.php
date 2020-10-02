<?php

namespace Repositories;

class ProductRepository extends \Libraries\Database
{
    public function getById($id): ?\Models\ProductModel
    {
        $this->query("SELECT * FROM \"products\" WHERE \"id\"=:id");
        $this->bindValue(":id", $id);
        $row = $this->fetch();
        if ($this->rowCount() > 0) {
            $product = new \Models\ProductModel;
            foreach ($row as $property => $value) {
                $product->$property = $value;
            }
            return $product;
        } else {
            return null;
        }
    }

    public function getList($where, $orderBy, $limit, $offset, $parameters = array())
    {
        $this->query("SELECT * FROM \"products\" {$where} {$orderBy} {$limit} {$offset}", $parameters);
        $rows = $this->fetchAll();
        $products = array();
        foreach ($rows as $row) {
            $product = array();
            foreach ($row as $property => $value) {
                $product[$property] = $value;
            }
            array_push($products, (object) $product);
        }
        return $products;
    }

    public function add(\Models\ProductModel $product): ?\Models\ProductModel
    {
        $this->query("SELECT * FROM \"products\" WHERE LOWER(\"sku\")=:sku");
        $this->bindValue(":sku", strtolower($product->sku));
        $row = $this->fetch();
        if ($this->rowCount() == 0) {
            $columns = array();
            $values = array();
            $parameters = array();
            foreach ($product as $property => $value) {
                if ($property != "id" && !is_null($value)) {
                    array_push($columns, "\"{$property}\"");
                    array_push($values, ":{$property}");
                    array_push($parameters, array("name" => ":{$property}", "value" => $value));
                }
            }
            $columns = implode(", ", $columns);
            $values = implode(", ", $values);
            $this->query("INSERT INTO \"products\" ({$columns}) VALUES ({$values})", $parameters);
            $this->execute();
            return $this->getById($this->lastInsertId());
        } else {
            return null;
        }
    }

    public function update(\Models\ProductModel $product): ?\Models\ProductModel
    {
        $this->query("SELECT * FROM \"products\" WHERE \"id\"!=:id AND LOWER(\"sku\")=:sku");
        $this->bindValue(":id", $product->id);
        $this->bindValue(":sku", strtolower($product->sku));
        $row = $this->fetch();
        if ($this->rowCount() == 0) {
            $columns = array();
            $parameters = array();
            foreach ($product as $property => $value) {
                if ($property != "id" && !is_null($value)) {
                    array_push($columns, "\"{$property}\"=:{$property}");
                    array_push($parameters, array("name" => ":{$property}", "value" => $value));
                }
            }
            $columns = implode(", ", $columns);
            $this->query("UPDATE \"products\" SET {$columns} WHERE \"id\"=:id", $parameters);
            $this->bindValue(":id", $product->id);
            foreach ($parameters as $parameter) {
                $this->bindValue($parameter["name"], $parameter["value"]);
            }
            $this->execute();
        } else {
            return null;
        }
    }

    public function delete(\Models\ProductModel $product)
    {
        $this->query("DELETE FROM \"products\"  WHERE \"id\"=:id");
        $this->bindValue(":id", $product->id);
        $row = $this->execute();
        if ($this->rowCount() == 0) {
            return true;
        } else {
            return null;
        }
    }
}
