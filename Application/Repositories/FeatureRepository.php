<?php

namespace Repositories;

class FeatureRepository extends \Libraries\Database
{
    public function getById($id): ?\Models\FeatureModel
    {
        $this->query("SELECT * FROM \"features\" WHERE \"id\"=:id");
        $this->bindValue(":id", $id);
        $row = $this->fetch();
        if ($this->rowCount() > 0) {
            $feature = new \Models\FeatureModel;
            foreach ($row as $property => $value) {
                $feature->$property = $value;
            }
            return $feature;
        } else {
            return null;
        }
    }

    public function getList($where, $orderBy, $limit, $offset, $parameters = array())
    {
        $this->query("SELECT * FROM \"features\" {$where} {$orderBy} {$limit} {$offset}", $parameters);
        $rows = $this->fetchAll();
        $features = array();
        foreach ($rows as $row) {
            $feature = array();
            foreach ($row as $property => $value) {
                $feature[$property] = $value;
            }
            array_push($features, (object) $feature);
        }
        return $features;
    }

    public function add(\Models\FeatureModel $feature): ?\Models\FeatureModel
    {
        $this->query("SELECT * FROM \"features\" WHERE \"product\"=:product AND LOWER(\"purpose\")=:purpose AND LOWER(\"type\")=:type AND LOWER(\"position\")=:position AND LOWER(\"value\")=:value");
        $this->bindValue(":product", $feature->product);
        $this->bindValue(":purpose", strtolower($feature->purpose));
        $this->bindValue(":type", strtolower($feature->type));
        $this->bindValue(":position", strtolower($feature->position));
        $this->bindValue(":value", strtolower($feature->value));
        $row = $this->fetch();
        if ($this->rowCount() == 0) {
            $columns = array();
            $values = array();
            $parameters = array();
            foreach ($feature as $property => $value) {
                if ($property != "id" && !is_null($value)) {
                    array_push($columns, "\"{$property}\"");
                    array_push($values, ":{$property}");
                    array_push($parameters, array("name" => ":{$property}", "value" => $value));
                }
            }
            $columns = implode(", ", $columns);
            $values = implode(", ", $values);
            $this->query("INSERT INTO \"features\" ({$columns}) VALUES ({$values})", $parameters);
            $this->execute();
            return $this->getById($this->lastInsertId());
        } else {
            return null;
        }
    }

    public function update(\Models\FeatureModel $feature): ?\Models\FeatureModel
    {
        $this->query("SELECT * FROM \"features\" WHERE \"id\"!=:id LOWER(\"product\")=:product AND LOWER(\"purpose\")=:purpose AND LOWER(\"type\")=:type AND \"position\"=:position AND LOWER(\"value\")=:value");
        $this->bindValue(":id", $feature->id);
        $this->bindValue(":product", strtolower($feature->product));
        $this->bindValue(":purpose", strtolower($feature->purpose));
        $this->bindValue(":type", strtolower($feature->type));
        $this->bindValue(":position", strtolower($feature->position));
        $this->bindValue(":value", strtolower($feature->value));
        $row = $this->fetch();
        if ($this->rowCount() == 0) {
            $columns = array();
            $parameters = array();
            foreach ($feature as $property => $value) {
                if ($property != "id" && !is_null($value)) {
                    array_push($columns, "\"{$property}\"=:{$property}");
                    array_push($parameters, array("name" => ":{$property}", "value" => $value));
                }
            }
            $columns = implode(", ", $columns);
            $this->query("UPDATE \"features\" SET {$columns} WHERE \"id\"=:id", $parameters);
            $this->bindValue(":id", $feature->id);
            foreach ($parameters as $parameter) {
                $this->bindValue($parameter["name"], $parameter["value"]);
            }
            $this->execute();
        } else {
            return null;
        }
    }

    public function delete(\Models\FeatureModel $feature)
    {
        $this->query("DELETE FROM \"features\"  WHERE \"id\"=:id");
        $this->bindValue(":id", $feature->id);
        $row = $this->execute();
        if ($this->rowCount() == 0) {
            return true;
        } else {
            return null;
        }
    }
}
