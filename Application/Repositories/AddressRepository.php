<?php

namespace Repositories;

class AddressRepository extends \Libraries\Database
{
    public function getById($id): ?\Models\AddressModel
    {
        $this->query("SELECT * FROM \"addresses\" WHERE \"id\"=:id");
        $this->bindValue(":id", $id);
        $row = $this->fetch();
        if ($this->rowCount() > 0) {
            $address = new \Models\AddressModel;
            foreach ($row as $property => $value) {
                $address->$property = $value;
            }
            return $address;
        } else {
            return null;
        }
    }

    public function getList($where, $orderBy, $limit, $offset, $parameters = array())
    {
        $this->query("SELECT * FROM \"addresses\" {$where} {$orderBy} {$limit} {$offset}", $parameters);
        $rows = $this->fetchAll();
        $addresses = array();
        foreach ($rows as $row) {
            $address = array();
            foreach ($row as $property => $value) {
                $address[$property] = $value;
            }
            array_push($addresses, (object) $address);
        }
        return $addresses;
    }

    public function add(\Models\AddressModel $address): ?\Models\AddressModel
    {
        $this->query("SELECT * FROM \"addresses\" WHERE LOWER(\"title\")=:title");
        $this->bindValue(":title", strtolower($address->title));
        $row = $this->fetch();
        if ($this->rowCount() == 0) {
            $columns = array();
            $values = array();
            $parameters = array();
            foreach ($address as $property => $value) {
                if ($property != "id" && !is_null($value)) {
                    array_push($columns, "\"{$property}\"");
                    array_push($values, ":{$property}");
                    array_push($parameters, array("name" => ":{$property}", "value" => $value));
                }
            }
            $columns = implode(", ", $columns);
            $values = implode(", ", $values);
            $this->query("INSERT INTO \"addresses\" ({$columns}) VALUES ({$values})", $parameters);
            $this->execute();
            return $this->getById($this->lastInsertId());
        } else {
            return null;
        }
    }

    public function update(\Models\AddressModel $address): ?\Models\AddressModel
    {
        $this->query("SELECT * FROM \"addresses\" WHERE \"id\"!=:id AND LOWER(\"title\")=:title");
        $this->bindValue(":id", $address->id);
        $this->bindValue(":title", strtolower($address->title));
        $row = $this->fetch();
        if ($this->rowCount() == 0) {
            $columns = array();
            $parameters = array();
            foreach ($address as $property => $value) {
                if ($property != "id" && !is_null($value)) {
                    array_push($columns, "\"{$property}\"=:{$property}");
                    array_push($parameters, array("name" => ":{$property}", "value" => $value));
                }
            }
            $columns = implode(", ", $columns);
            $this->query("UPDATE \"addresses\" SET {$columns} WHERE \"id\"=:id", $parameters);
            $this->bindValue(":id", $address->id);
            foreach ($parameters as $parameter) {
                $this->bindValue($parameter["name"], $parameter["value"]);
            }
            $this->execute();
        } else {
            return null;
        }
    }

    public function delete(\Models\AddressModel $address)
    {
        $this->query("DELETE FROM \"addresses\"  WHERE \"id\"=:id");
        $this->bindValue(":id", $address->id);
        $row = $this->execute();
        if ($this->rowCount() == 0) {
            return true;
        } else {
            return null;
        }
    }
}
