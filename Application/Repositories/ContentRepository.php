<?php

namespace Repositories;

class ContentRepository extends \Libraries\Database
{
    public function getById($id): ?\Models\ContentModel
    {
        $this->query("SELECT * FROM \"contents\" WHERE \"id\"=:id");
        $this->bindValue(":id", $id);
        $row = $this->fetch();
        if ($this->rowCount() > 0) {
            $content = new \Models\ContentModel;
            foreach ($row as $property => $value) {
                $content->$property = $value;
            }
            return $content;
        } else {
            return null;
        }
    }

    public function getList($where, $orderBy, $limit, $offset, $parameters = array())
    {
        $this->query("SELECT * FROM \"contents\" {$where} {$orderBy} {$limit} {$offset}", $parameters);
        $rows = $this->fetchAll();
        $contents = array();
        foreach ($rows as $row) {
            $content = array();
            foreach ($row as $property => $value) {
                $content[$property] = $value;
            }
            array_push($contents, (object) $content);
        }
        return $contents;
    }

    public function add(\Models\ContentModel $content): ?\Models\ContentModel
    {
        $this->query("SELECT * FROM \"contents\" WHERE LOWER(\"title\")=:title");
        $this->bindValue(":title", strtolower($content->title));
        $row = $this->fetch();
        if ($this->rowCount() == 0) {
            $columns = array();
            $values = array();
            $parameters = array();
            foreach ($content as $property => $value) {
                if ($property != "id" && !is_null($value)) {
                    array_push($columns, "\"{$property}\"");
                    array_push($values, ":{$property}");
                    array_push($parameters, array("name" => ":{$property}", "value" => $value));
                }
            }
            $columns = implode(", ", $columns);
            $values = implode(", ", $values);
            $this->query("INSERT INTO \"contents\" ({$columns}) VALUES ({$values})", $parameters);
            $this->execute();
            return $this->getById($this->lastInsertId());
        } else {
            return null;
        }
    }

    public function update(\Models\ContentModel $content): ?\Models\ContentModel
    {
        $this->query("SELECT * FROM \"contents\" WHERE \"id\"!=:id LOWER(\"title\")=:title");
        $this->bindValue(":id", $content->id);
        $this->bindValue(":title", strtolower($content->title));
        $row = $this->fetch();
        if ($this->rowCount() == 0) {
            $columns = array();
            $parameters = array();
            foreach ($content as $property => $value) {
                if ($property != "id" && !is_null($value)) {
                    array_push($columns, "\"{$property}\"=:{$property}");
                    array_push($parameters, array("name" => ":{$property}", "value" => $value));
                }
            }
            $columns = implode(", ", $columns);
            $this->query("UPDATE \"contents\" SET {$columns} WHERE \"id\"=:id", $parameters);
            $this->bindValue(":id", $content->id);
            foreach ($parameters as $parameter) {
                $this->bindValue($parameter["name"], $parameter["value"]);
            }
            $this->execute();
        } else {
            return null;
        }
    }

    public function delete(\Models\ContentModel $content)
    {
        $this->query("DELETE FROM \"contents\"  WHERE \"id\"=:id");
        $this->bindValue(":id", $content->id);
        $row = $this->execute();
        if ($this->rowCount() == 0) {
            return true;
        } else {
            return null;
        }
    }
}
