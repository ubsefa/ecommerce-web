<?php

namespace Repositories;

class ShippingRepository extends \Libraries\Database
{
    public function getById($id): ?\Models\ShippingModel
    {
        $this->query("SELECT * FROM \"shippings\" WHERE \"id\"=:id");
        $this->bindValue(":id", $id);
        $row = $this->fetch();
        if ($this->rowCount() > 0) {
            $shipping = new \Models\ShippingModel;
            foreach ($row as $property => $value) {
                $shipping->$property = $value;
            }
            return $shipping;
        } else {
            return null;
        }
    }

    public function getList($where, $orderBy, $limit, $offset, $parameters = array())
    {
        $this->query("SELECT * FROM \"shippings\" {$where} {$orderBy} {$limit} {$offset}", $parameters);
        $rows = $this->fetchAll();
        $shippings = array();
        foreach ($rows as $row) {
            $shipping = array();
            foreach ($row as $property => $value) {
                $shipping[$property] = $value;
            }
            array_push($shippings, (object) $shipping);
        }
        return $shippings;
    }

    public function add(\Models\ShippingModel $shipping): ?\Models\ShippingModel
    {
        $this->query("SELECT * FROM \"shippings\" WHERE LOWER(\"title\")=:title");
        $this->bindValue(":title", strtolower($shipping->title));
        $row = $this->fetch();
        if ($this->rowCount() == 0) {
            $columns = array();
            $values = array();
            $parameters = array();
            foreach ($shipping as $property => $value) {
                if ($property != "id" && !is_null($value)) {
                    array_push($columns, "\"{$property}\"");
                    array_push($values, ":{$property}");
                    array_push($parameters, array("name" => ":{$property}", "value" => $value));
                }
            }
            $columns = implode(", ", $columns);
            $values = implode(", ", $values);
            $this->query("INSERT INTO \"shippings\" ({$columns}) VALUES ({$values})", $parameters);
            $this->execute();
            return $this->getById($this->lastInsertId());
        } else {
            return null;
        }
    }

    public function update(\Models\ShippingModel $shipping): ?\Models\ShippingModel
    {
        $this->query("SELECT * FROM \"shippings\" WHERE \"id\"!=:id AND LOWER(\"title\")=:title");
        $this->bindValue(":id", $shipping->id);
        $this->bindValue(":title", strtolower($shipping->title));
        $row = $this->fetch();
        if ($this->rowCount() == 0) {
            $columns = array();
            $parameters = array();
            foreach ($shipping as $property => $value) {
                if ($property != "id" && !is_null($value)) {
                    array_push($columns, "\"{$property}\"=:{$property}");
                    array_push($parameters, array("name" => ":{$property}", "value" => $value));
                }
            }
            $columns = implode(", ", $columns);
            $this->query("UPDATE \"shippings\" SET {$columns} WHERE \"id\"=:id", $parameters);
            $this->bindValue(":id", $shipping->id);
            foreach ($parameters as $parameter) {
                $this->bindValue($parameter["name"], $parameter["value"]);
            }
            $this->execute();
        } else {
            return null;
        }
    }

    public function delete(\Models\ShippingModel $shipping)
    {
        $this->query("DELETE FROM \"shippings\"  WHERE \"id\"=:id");
        $this->bindValue(":id", $shipping->id);
        $row = $this->execute();
        if ($this->rowCount() == 0) {
            return true;
        } else {
            return null;
        }
    }
}
