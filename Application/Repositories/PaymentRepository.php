<?php

namespace Repositories;

class PaymentRepository extends \Libraries\Database
{
    public function getById($id): ?\Models\PaymentModel
    {
        $this->query("SELECT * FROM \"payments\" WHERE \"id\"=:id");
        $this->bindValue(":id", $id);
        $row = $this->fetch();
        if ($this->rowCount() > 0) {
            $payment = new \Models\PaymentModel;
            foreach ($row as $property => $value) {
                $payment->$property = $value;
            }
            return $payment;
        } else {
            return null;
        }
    }

    public function getList($where, $orderBy, $limit, $offset, $parameters = array())
    {
        $this->query("SELECT * FROM \"payments\" {$where} {$orderBy} {$limit} {$offset}", $parameters);
        $rows = $this->fetchAll();
        $payments = array();
        foreach ($rows as $row) {
            $payment = array();
            foreach ($row as $property => $value) {
                $payment[$property] = $value;
            }
            array_push($payments, (object) $payment);
        }
        return $payments;
    }

    public function add(\Models\PaymentModel $payment): ?\Models\PaymentModel
    {
        $this->query("SELECT * FROM \"payments\" WHERE \"order\"=:order AND \"allowed\"=:allowed");
        $this->bindValue(":order", $payment->order);
        $this->bindValue(":allowed", "b1");
        $row = $this->fetch();
        if ($this->rowCount() == 0) {
            $columns = array();
            $values = array();
            $parameters = array();
            foreach ($payment as $property => $value) {
                if ($property != "id" && !is_null($value)) {
                    array_push($columns, "\"{$property}\"");
                    array_push($values, ":{$property}");
                    array_push($parameters, array("name" => ":{$property}", "value" => $value));
                }
            }
            $columns = implode(", ", $columns);
            $values = implode(", ", $values);
            $this->query("INSERT INTO \"payments\" ({$columns}) VALUES ({$values})", $parameters);
            $this->execute();
            return $this->getById($this->lastInsertId());
        } else {
            return null;
        }
    }

    public function update(\Models\PaymentModel $payment): ?\Models\PaymentModel
    {
        $this->query("SELECT * FROM \"payments\" WHERE \"id\"!=:id AND \"order\"=:order AND \"allowed\"=:allowed");
        $this->bindValue(":id", $payment->id);
        $this->bindValue(":order", $payment->order);
        $this->bindValue(":allowed", "b1");
        $row = $this->fetch();
        if ($this->rowCount() == 0) {
            $columns = array();
            $parameters = array();
            foreach ($payment as $property => $value) {
                if ($property != "id" && !is_null($value)) {
                    array_push($columns, "\"{$property}\"=:{$property}");
                    array_push($parameters, array("name" => ":{$property}", "value" => $value));
                }
            }
            $columns = implode(", ", $columns);
            $this->query("UPDATE \"payments\" SET {$columns} WHERE \"id\"=:id", $parameters);
            $this->bindValue(":id", $payment->id);
            foreach ($parameters as $parameter) {
                $this->bindValue($parameter["name"], $parameter["value"]);
            }
            $this->execute();
        } else {
            return null;
        }
    }

    public function delete(\Models\PaymentModel $payment)
    {
        $this->query("DELETE FROM \"payments\"  WHERE \"id\"=:id");
        $this->bindValue(":id", $payment->id);
        $row = $this->execute();
        if ($this->rowCount() == 0) {
            return true;
        } else {
            return null;
        }
    }
}
