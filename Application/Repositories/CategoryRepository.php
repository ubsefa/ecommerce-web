<?php

namespace Repositories;

class CategoryRepository extends \Libraries\Database
{
    public function getById($id): ?\Models\CategoryModel
    {
        $this->query("SELECT * FROM \"categories\" WHERE \"id\"=:id");
        $this->bindValue(":id", $id);
        $row = $this->fetch();
        if ($this->rowCount() > 0) {
            $category = new \Models\CategoryModel;
            foreach ($row as $property => $value) {
                $category->$property = $value;
            }
            return $category;
        } else {
            return null;
        }
    }

    public function getList($where, $orderBy, $limit, $offset, $parameters = array())
    {
        $this->query("SELECT * FROM \"categories\" {$where} {$orderBy} {$limit} {$offset}", $parameters);
        $rows = $this->fetchAll();
        $categories = array();
        foreach ($rows as $row) {
            $category = array();
            foreach ($row as $property => $value) {
                $category[$property] = $value;
            }
            array_push($categories, (object) $category);
        }
        return $categories;
    }

    public function add(\Models\CategoryModel $category): ?\Models\CategoryModel
    {
        $this->query("SELECT * FROM \"categories\" WHERE LOWER(\"title\")=:title");
        $this->bindValue(":title", strtolower($category->title));
        $row = $this->fetch();
        if ($this->rowCount() == 0) {
            $columns = array();
            $values = array();
            $parameters = array();
            foreach ($category as $property => $value) {
                if ($property != "id" && !is_null($value)) {
                    array_push($columns, "\"{$property}\"");
                    array_push($values, ":{$property}");
                    array_push($parameters, array("name" => ":{$property}", "value" => $value));
                }
            }
            $columns = implode(", ", $columns);
            $values = implode(", ", $values);
            $this->query("INSERT INTO \"categories\" ({$columns}) VALUES ({$values})", $parameters);
            $this->execute();
            return $this->getById($this->lastInsertId());
        } else {
            return null;
        }
    }

    public function update(\Models\CategoryModel $category): ?\Models\CategoryModel
    {
        $this->query("SELECT * FROM \"categories\" WHERE \"id\"!=:id AND LOWER(\"title\")=:title");
        $this->bindValue(":id", $category->id);
        $this->bindValue(":title", strtolower($category->title));
        $row = $this->fetch();
        if ($this->rowCount() == 0) {
            $columns = array();
            $parameters = array();
            foreach ($category as $property => $value) {
                if ($property != "id" && !is_null($value)) {
                    array_push($columns, "\"{$property}\"=:{$property}");
                    array_push($parameters, array("name" => ":{$property}", "value" => $value));
                }
            }
            $columns = implode(", ", $columns);
            $this->query("UPDATE \"categories\" SET {$columns} WHERE \"id\"=:id", $parameters);
            $this->bindValue(":id", $category->id);
            foreach ($parameters as $parameter) {
                $this->bindValue($parameter["name"], $parameter["value"]);
            }
            $this->execute();
        } else {
            return null;
        }
    }

    public function delete(\Models\CategoryModel $category)
    {
        $this->query("DELETE FROM \"categories\"  WHERE \"id\"=:id");
        $this->bindValue(":id", $category->id);
        $row = $this->execute();
        if ($this->rowCount() == 0) {
            return true;
        } else {
            return null;
        }
    }
}
