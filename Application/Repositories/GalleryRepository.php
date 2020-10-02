<?php

namespace Repositories;

class GalleryRepository extends \Libraries\Database
{
    public function getById($id): ?\Models\GalleryModel
    {
        $this->query("SELECT * FROM \"galleries\" WHERE \"id\"=:id");
        $this->bindValue(":id", $id);
        $row = $this->fetch();
        if ($this->rowCount() > 0) {
            $gallery = new \Models\GalleryModel;
            foreach ($row as $property => $value) {
                $gallery->$property = $value;
            }
            return $gallery;
        } else {
            return null;
        }
    }

    public function getList($where, $orderBy, $limit, $offset, $parameters = array())
    {
        $this->query("SELECT * FROM \"galleries\" {$where} {$orderBy} {$limit} {$offset}", $parameters);
        $rows = $this->fetchAll();
        $galleries = array();
        foreach ($rows as $row) {
            $gallery = array();
            foreach ($row as $property => $value) {
                $gallery[$property] = $value;
            }
            array_push($galleries, (object) $gallery);
        }
        return $galleries;
    }

    public function add(\Models\GalleryModel $gallery): ?\Models\GalleryModel
    {
        $this->query("SELECT * FROM \"galleries\" WHERE \"product\"=:product AND LOWER(\"title\")=:title");
        $this->bindValue(":product", $gallery->product);
        $this->bindValue(":title", strtolower($gallery->title));
        $row = $this->fetch();
        if ($this->rowCount() == 0) {
            $columns = array();
            $values = array();
            $parameters = array();
            foreach ($gallery as $property => $value) {
                if ($property != "id" && !is_null($value)) {
                    array_push($columns, "\"{$property}\"");
                    array_push($values, ":{$property}");
                    array_push($parameters, array("name" => ":{$property}", "value" => $value));
                }
            }
            $columns = implode(", ", $columns);
            $values = implode(", ", $values);
            $this->query("INSERT INTO \"galleries\" ({$columns}) VALUES ({$values})", $parameters);
            $this->execute();
            return $this->getById($this->lastInsertId());
        } else {
            return null;
        }
    }

    public function update(\Models\GalleryModel $gallery): ?\Models\GalleryModel
    {
        $this->query("SELECT * FROM \"galleries\" WHERE \"id\"!=:id AND \"product\"=:product AND LOWER(\"title\")=:title");
        $this->bindValue(":id", $gallery->id);
        $this->bindValue(":product", $gallery->product);
        $this->bindValue(":title", strtolower($gallery->title));
        $row = $this->fetch();
        if ($this->rowCount() == 0) {
            $columns = array();
            $parameters = array();
            foreach ($gallery as $property => $value) {
                if ($property != "id" && !is_null($value)) {
                    array_push($columns, "\"{$property}\"=:{$property}");
                    array_push($parameters, array("name" => ":{$property}", "value" => $value));
                }
            }
            $columns = implode(", ", $columns);
            $this->query("UPDATE \"galleries\" SET {$columns} WHERE \"id\"=:id", $parameters);
            $this->bindValue(":id", $gallery->id);
            foreach ($parameters as $parameter) {
                $this->bindValue($parameter["name"], $parameter["value"]);
            }
            $this->execute();
        } else {
            return null;
        }
    }

    public function delete(\Models\GalleryModel $gallery)
    {
        $this->query("DELETE FROM \"galleries\"  WHERE \"id\"=:id");
        $this->bindValue(":id", $gallery->id);
        $row = $this->execute();
        if ($this->rowCount() == 0) {
            return true;
        } else {
            return null;
        }
    }
}
