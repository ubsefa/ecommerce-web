<?php

namespace Repositories;

class InvoiceRepository extends \Libraries\Database
{
    public function getById($id): ?\Models\InvoiceModel
    {
        $this->query("SELECT * FROM \"invoices\" WHERE \"id\"=:id");
        $this->bindValue(":id", $id);
        $row = $this->fetch();
        if ($this->rowCount() > 0) {
            $invoice = new \Models\InvoiceModel;
            foreach ($row as $property => $value) {
                $invoice->$property = $value;
            }
            return $invoice;
        } else {
            return null;
        }
    }

    public function getList($where, $orderBy, $limit, $offset, $parameters = array())
    {
        $this->query("SELECT * FROM \"invoices\" {$where} {$orderBy} {$limit} {$offset}", $parameters);
        $rows = $this->fetchAll();
        $invoices = array();
        foreach ($rows as $row) {
            $invoice = array();
            foreach ($row as $property => $value) {
                $invoice[$property] = $value;
            }
            array_push($invoices, (object) $invoice);
        }
        return $invoices;
    }

    public function add(\Models\InvoiceModel $invoice): ?\Models\InvoiceModel
    {
        $this->query("SELECT * FROM \"invoices\" WHERE \"cart\"=:cart AND LOWER(\"number\")=:number");
        $this->bindValue(":cart", $invoice->cart);
        $this->bindValue(":number", strtolower($invoice->number));
        $row = $this->fetch();
        if ($this->rowCount() == 0) {
            $columns = array();
            $values = array();
            $parameters = array();
            foreach ($invoice as $property => $value) {
                if ($property != "id" && !is_null($value)) {
                    array_push($columns, "\"{$property}\"");
                    array_push($values, ":{$property}");
                    array_push($parameters, array("name" => ":{$property}", "value" => $value));
                }
            }
            $columns = implode(", ", $columns);
            $values = implode(", ", $values);
            $this->query("INSERT INTO \"invoices\" ({$columns}) VALUES ({$values})", $parameters);
            $this->execute();
            return $this->getById($this->lastInsertId());
        } else {
            return null;
        }
    }

    public function update(\Models\InvoiceModel $invoice): ?\Models\InvoiceModel
    {
        $this->query("SELECT * FROM \"invoices\" WHERE \"id\"!=:id AND \"cart\"=:cart AND LOWER(\"number\")=:number");
        $this->bindValue(":id", $invoice->id);
        $this->bindValue(":cart", $invoice->cart);
        $this->bindValue(":number", strtolower($invoice->number));
        $row = $this->fetch();
        if ($this->rowCount() == 0) {
            $columns = array();
            $parameters = array();
            foreach ($invoice as $property => $value) {
                if ($property != "id" && !is_null($value)) {
                    array_push($columns, "\"{$property}\"=:{$property}");
                    array_push($parameters, array("name" => ":{$property}", "value" => $value));
                }
            }
            $columns = implode(", ", $columns);
            $this->query("UPDATE \"invoices\" SET {$columns} WHERE \"id\"=:id", $parameters);
            $this->bindValue(":id", $invoice->id);
            foreach ($parameters as $parameter) {
                $this->bindValue($parameter["name"], $parameter["value"]);
            }
            $this->execute();
        } else {
            return null;
        }
    }

    public function delete(\Models\InvoiceModel $invoice)
    {
        $this->query("DELETE FROM \"invoices\"  WHERE \"id\"=:id");
        $this->bindValue(":id", $invoice->id);
        $row = $this->execute();
        if ($this->rowCount() == 0) {
            return true;
        } else {
            return null;
        }
    }
}
