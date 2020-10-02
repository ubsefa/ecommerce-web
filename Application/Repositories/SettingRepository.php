<?php

namespace Repositories;

class SettingRepository extends \Libraries\Database
{
    public function getById($id): ?\Models\SettingModel
    {
        $this->query("SELECT * FROM \"settings\" WHERE \"id\"=:id");
        $this->bindValue(":id", $id);
        $row = $this->fetch();
        if ($this->rowCount() > 0) {
            $setting = new \Models\SettingModel;
            foreach ($row as $property => $value) {
                $setting->$property = $value;
            }
            return $setting;
        } else {
            return null;
        }
    }

    public function getList($where, $orderBy, $limit, $offset, $parameters = array())
    {
        $this->query("SELECT * FROM \"settings\" {$where} {$orderBy} {$limit} {$offset}", $parameters);
        $rows = $this->fetchAll();
        $settings = array();
        foreach ($rows as $row) {
            $setting = array();
            foreach ($row as $property => $value) {
                $setting[$property] = $value;
            }
            array_push($settings, (object) $setting);
        }
        return $settings;
    }

    public function add(\Models\SettingModel $setting): ?\Models\SettingModel
    {
        $this->query("SELECT * FROM \"settings\" WHERE LOWER(\"title\")=:title");
        $this->bindValue(":title", strtolower($setting->title));
        $row = $this->fetch();
        if ($this->rowCount() == 0) {
            $columns = array();
            $values = array();
            $parameters = array();
            foreach ($setting as $property => $value) {
                if ($property != "id" && !is_null($value)) {
                    array_push($columns, "\"{$property}\"");
                    array_push($values, ":{$property}");
                    array_push($parameters, array("name" => ":{$property}", "value" => $value));
                }
            }
            $columns = implode(", ", $columns);
            $values = implode(", ", $values);
            $this->query("INSERT INTO \"settings\" ({$columns}) VALUES ({$values})", $parameters);
            $this->execute();
            return $this->getById($this->lastInsertId());
        } else {
            return null;
        }
    }

    public function update(\Models\SettingModel $setting): ?\Models\SettingModel
    {
        $this->query("SELECT * FROM \"settings\" WHERE \"id\"!=:id AND LOWER(\"title\")=:title");
        $this->bindValue(":id", $setting->id);
        $this->bindValue(":title", strtolower($setting->title));
        $row = $this->fetch();
        if ($this->rowCount() == 0) {
            $columns = array();
            $parameters = array();
            foreach ($setting as $property => $value) {
                if ($property != "id" && !is_null($value)) {
                    array_push($columns, "\"{$property}\"=:{$property}");
                    array_push($parameters, array("name" => ":{$property}", "value" => $value));
                }
            }
            $columns = implode(", ", $columns);
            $this->query("UPDATE \"settings\" SET {$columns} WHERE \"id\"=:id", $parameters);
            $this->bindValue(":id", $setting->id);
            foreach ($parameters as $parameter) {
                $this->bindValue($parameter["name"], $parameter["value"]);
            }
            $this->execute();
        } else {
            return null;
        }
    }

    public function delete(\Models\SettingModel $setting)
    {
        $this->query("DELETE FROM \"settings\"  WHERE \"id\"=:id");
        $this->bindValue(":id", $setting->id);
        $row = $this->execute();
        if ($this->rowCount() == 0) {
            return true;
        } else {
            return null;
        }
    }
}
