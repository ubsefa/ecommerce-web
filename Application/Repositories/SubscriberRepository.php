<?php

namespace Repositories;

class SubscriberRepository extends \Libraries\Database
{
    public function getById($id): ?\Models\SubscriberModel
    {
        $this->query("SELECT * FROM \"subscribers\" WHERE \"id\"=:id");
        $this->bindValue(":id", $id);
        $row = $this->fetch();
        if ($this->rowCount() > 0) {
            $subscriber = new \Models\SubscriberModel;
            foreach ($row as $property => $value) {
                $subscriber->$property = $value;
            }
            return $subscriber;
        } else {
            return null;
        }
    }

    public function getList($where, $orderBy, $limit, $offset, $parameters = array())
    {
        $this->query("SELECT * FROM \"subscribers\" {$where} {$orderBy} {$limit} {$offset}", $parameters);
        $rows = $this->fetchAll();
        $subscribers = array();
        foreach ($rows as $row) {
            $subscriber = array();
            foreach ($row as $property => $value) {
                $subscriber[$property] = $value;
            }
            array_push($subscribers, (object) $subscriber);
        }
        return $subscribers;
    }

    public function add(\Models\SubscriberModel $subscriber): ?\Models\SubscriberModel
    {
        $this->query("SELECT * FROM \"subscribers\" WHERE LOWER(\"email\")=:email");
        $this->bindValue(":email", strtolower($subscriber->email));
        $row = $this->fetch();
        if ($this->rowCount() == 0) {
            $columns = array();
            $values = array();
            $parameters = array();
            foreach ($subscriber as $property => $value) {
                if ($property != "id" && !is_null($value)) {
                    array_push($columns, "\"{$property}\"");
                    array_push($values, ":{$property}");
                    array_push($parameters, array("name" => ":{$property}", "value" => $value));
                }
            }
            $columns = implode(", ", $columns);
            $values = implode(", ", $values);
            $this->query("INSERT INTO \"subscribers\" ({$columns}) VALUES ({$values})", $parameters);
            $this->execute();
            return $this->getById($this->lastInsertId());
        } else {
            return null;
        }
    }

    public function update(\Models\SubscriberModel $subscriber): ?\Models\SubscriberModel
    {
        $this->query("SELECT * FROM \"subscribers\" WHERE \"id\"!=:id AND LOWER(\"email\")=:email");
        $this->bindValue(":id", $subscriber->id);
        $this->bindValue(":email", strtolower($subscriber->email));
        $row = $this->fetch();
        if ($this->rowCount() == 0) {
            $columns = array();
            $parameters = array();
            foreach ($subscriber as $property => $value) {
                if ($property != "id" && !is_null($value)) {
                    array_push($columns, "\"{$property}\"=:{$property}");
                    array_push($parameters, array("name" => ":{$property}", "value" => $value));
                }
            }
            $columns = implode(", ", $columns);
            $this->query("UPDATE \"subscribers\" SET {$columns} WHERE \"id\"=:id", $parameters);
            $this->bindValue(":id", $subscriber->id);
            foreach ($parameters as $parameter) {
                $this->bindValue($parameter["name"], $parameter["value"]);
            }
            $this->execute();
        } else {
            return null;
        }
    }

    public function delete(\Models\SubscriberModel $subscriber)
    {
        $this->query("DELETE FROM \"subscribers\"  WHERE \"id\"=:id");
        $this->bindValue(":id", $subscriber->id);
        $row = $this->execute();
        if ($this->rowCount() == 0) {
            return true;
        } else {
            return null;
        }
    }
}
