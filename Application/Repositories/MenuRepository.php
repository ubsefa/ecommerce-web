<?php

namespace Repositories;

class MenuRepository extends \Libraries\Database
{
    public function getById($id): ?\Models\MenuModel
    {
        $this->query("SELECT * FROM \"menus\" WHERE \"id\"=:id");
        $this->bindValue(":id", $id);
        $row = $this->fetch();
        if ($this->rowCount() > 0) {
            $menu = new \Models\MenuModel;
            foreach ($row as $property => $value) {
                $menu->$property = $value;
            }
            return $menu;
        } else {
            return null;
        }
    }

    public function getList($where, $orderBy, $limit, $offset, $parameters = array())
    {
        $this->query("SELECT * FROM \"menus\" {$where} {$orderBy} {$limit} {$offset}", $parameters);
        $rows = $this->fetchAll();
        $menus = array();
        foreach ($rows as $row) {
            $menu = array();
            foreach ($row as $property => $value) {
                $menu[$property] = $value;
            }
            array_push($menus, (object) $menu);
        }
        return $menus;
    }

    public function add(\Models\MenuModel $menu): ?\Models\MenuModel
    {
        $this->query("SELECT * FROM \"menus\" WHERE \"parent\"=:parent AND \"position\"=:position AND \"title\"=:title");
        $this->bindValue(":parent", $menu->parent);
        $this->bindValue(":position", $menu->position);
        $this->bindValue(":title", strtolower($menu->title));
        $row = $this->fetch();
        if ($this->rowCount() == 0) {
            $columns = array();
            $values = array();
            $parameters = array();
            foreach ($menu as $property => $value) {
                if ($property != "id" && !is_null($value)) {
                    array_push($columns, "\"{$property}\"");
                    array_push($values, ":{$property}");
                    array_push($parameters, array("name" => ":{$property}", "value" => $value));
                }
            }
            $columns = implode(", ", $columns);
            $values = implode(", ", $values);
            $this->query("INSERT INTO \"menus\" ({$columns}) VALUES ({$values})", $parameters);
            $this->execute();
            return $this->getById($this->lastInsertId());
        } else {
            return null;
        }
    }

    public function update(\Models\MenuModel $menu): ?\Models\MenuModel
    {
        $this->query("SELECT * FROM \"menus\" WHERE \"id\"!=:id AND \"parent\"=:parent AND \"position\"=:position AND \"title\"=:title");
        $this->bindValue(":id", $menu->id);
        $this->bindValue(":parent", $menu->parent);
        $this->bindValue(":position", $menu->position);
        $this->bindValue(":title", strtolower($menu->title));
        $row = $this->fetch();
        if ($this->rowCount() == 0) {
            $columns = array();
            $parameters = array();
            foreach ($menu as $property => $value) {
                if ($property != "id" && !is_null($value)) {
                    array_push($columns, "\"{$property}\"=:{$property}");
                    array_push($parameters, array("name" => ":{$property}", "value" => $value));
                }
            }
            $columns = implode(", ", $columns);
            $this->query("UPDATE \"menus\" SET {$columns} WHERE \"id\"=:id", $parameters);
            $this->bindValue(":id", $menu->id);
            foreach ($parameters as $parameter) {
                $this->bindValue($parameter["name"], $parameter["value"]);
            }
            $this->execute();
        } else {
            return null;
        }
    }

    public function delete(\Models\MenuModel $menu)
    {
        $this->query("DELETE FROM \"menus\"  WHERE \"id\"=:id");
        $this->bindValue(":id", $menu->id);
        $row = $this->execute();
        if ($this->rowCount() == 0) {
            return true;
        } else {
            return null;
        }
    }
}
