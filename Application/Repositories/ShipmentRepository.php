<?php

namespace Repositories;

class ShipmentRepository extends \Libraries\Database
{
    public function getById($id): ?\Models\ShipmentModel
    {
        $this->query("SELECT * FROM \"shipments\" WHERE \"id\"=:id");
        $this->bindValue(":id", $id);
        $row = $this->fetch();
        if ($this->rowCount() > 0) {
            $shipment = new \Models\ShipmentModel;
            foreach ($row as $property => $value) {
                $shipment->$property = $value;
            }
            return $shipment;
        } else {
            return null;
        }
    }

    public function getList($where, $orderBy, $limit, $offset, $parameters = array())
    {
        $this->query("SELECT * FROM \"shipments\" {$where} {$orderBy} {$limit} {$offset}", $parameters);
        $rows = $this->fetchAll();
        $shipments = array();
        foreach ($rows as $row) {
            $shipment = array();
            foreach ($row as $property => $value) {
                $shipment[$property] = $value;
            }
            array_push($shipments, (object) $shipment);
        }
        return $shipments;
    }

    public function add(\Models\ShipmentModel $shipment): ?\Models\ShipmentModel
    {
        $this->query("SELECT * FROM \"shipments\" WHERE \"shipping\"=:shipping AND \"cart\"=:cart");
        $this->bindValue(":shipping", $shipment->shipping);
        $this->bindValue(":cart", $shipment->cart);
        $row = $this->fetch();
        if ($this->rowCount() == 0) {
            $columns = array();
            $values = array();
            $parameters = array();
            foreach ($shipment as $property => $value) {
                if ($property != "id" && !is_null($value)) {
                    array_push($columns, "\"{$property}\"");
                    array_push($values, ":{$property}");
                    array_push($parameters, array("name" => ":{$property}", "value" => $value));
                }
            }
            $columns = implode(", ", $columns);
            $values = implode(", ", $values);
            $this->query("INSERT INTO \"shipments\" ({$columns}) VALUES ({$values})", $parameters);
            $this->execute();
            return $this->getById($this->lastInsertId());
        } else {
            return null;
        }
    }

    public function update(\Models\ShipmentModel $shipment): ?\Models\ShipmentModel
    {
        $this->query("SELECT * FROM \"shipments\" WHERE \"id\"!=:id AND \"shipping\"=:shipping AND \"cart\"=:cart");
        $this->bindValue(":id", $shipment->id);
        $this->bindValue(":shipping", $shipment->shipping);
        $this->bindValue(":cart", $shipment->cart);
        $row = $this->fetch();
        if ($this->rowCount() == 0) {
            $columns = array();
            $parameters = array();
            foreach ($shipment as $property => $value) {
                if ($property != "id" && !is_null($value)) {
                    array_push($columns, "\"{$property}\"=:{$property}");
                    array_push($parameters, array("name" => ":{$property}", "value" => $value));
                }
            }
            $columns = implode(", ", $columns);
            $this->query("UPDATE \"shipments\" SET {$columns} WHERE \"id\"=:id", $parameters);
            $this->bindValue(":id", $shipment->id);
            foreach ($parameters as $parameter) {
                $this->bindValue($parameter["name"], $parameter["value"]);
            }
            $this->execute();
        } else {
            return null;
        }
    }

    public function delete(\Models\ShipmentModel $shipment)
    {
        $this->query("DELETE FROM \"shipments\"  WHERE \"id\"=:id");
        $this->bindValue(":id", $shipment->id);
        $row = $this->execute();
        if ($this->rowCount() == 0) {
            return true;
        } else {
            return null;
        }
    }
}
