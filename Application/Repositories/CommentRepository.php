<?php

namespace Repositories;

class CommentRepository extends \Libraries\Database
{
    public function getById($id): ?\Models\CommentModel
    {
        $this->query("SELECT * FROM \"comments\" WHERE \"id\"=:id");
        $this->bindValue(":id", $id);
        $row = $this->fetch();
        if ($this->rowCount() > 0) {
            $comment = new \Models\CommentModel;
            foreach ($row as $property => $value) {
                $comment->$property = $value;
            }
            return $comment;
        } else {
            return null;
        }
    }

    public function getList($where, $orderBy, $limit, $offset, $parameters = array())
    {
        $this->query("SELECT * FROM \"comments\" {$where} {$orderBy} {$limit} {$offset}", $parameters);
        $rows = $this->fetchAll();
        $comments = array();
        foreach ($rows as $row) {
            $comment = array();
            foreach ($row as $property => $value) {
                $comment[$property] = $value;
            }
            array_push($comments, (object) $comment);
        }
        return $comments;
    }

    public function add(\Models\CommentModel $comment): ?\Models\CommentModel
    {
        $this->query("SELECT * FROM \"comments\" WHERE \"creator\"=:creator AND \"product\"=:product AND LOWER(\"comment\")=:comment");
        $this->bindValue(":creator", $comment->creator);
        $this->bindValue(":product", $comment->product);
        $this->bindValue(":comment", strtolower($comment->comment));
        $row = $this->fetch();
        if ($this->rowCount() == 0) {
            $columns = array();
            $values = array();
            $parameters = array();
            foreach ($comment as $property => $value) {
                if ($property != "id" && !is_null($value)) {
                    array_push($columns, "\"{$property}\"");
                    array_push($values, ":{$property}");
                    array_push($parameters, array("name" => ":{$property}", "value" => $value));
                }
            }
            $columns = implode(", ", $columns);
            $values = implode(", ", $values);
            $this->query("INSERT INTO \"comments\" ({$columns}) VALUES ({$values})", $parameters);
            $this->execute();
            return $this->getById($this->lastInsertId());
        } else {
            return null;
        }
    }

    public function update(\Models\CommentModel $comment): ?\Models\CommentModel
    {
        $this->query("SELECT * FROM \"comments\" WHERE \"id\"!=:id AND \"creator\"=:creator AND \"product\"=:product AND LOWER(\"comment\")=:comment");
        $this->bindValue(":id", $comment->id);
        $this->bindValue(":creator", $comment->creator);
        $this->bindValue(":product", $comment->product);
        $this->bindValue(":comment", strtolower($comment->comment));
        $row = $this->fetch();
        if ($this->rowCount() == 0) {
            $columns = array();
            $parameters = array();
            foreach ($comment as $property => $value) {
                if ($property != "id" && !is_null($value)) {
                    array_push($columns, "\"{$property}\"=:{$property}");
                    array_push($parameters, array("name" => ":{$property}", "value" => $value));
                }
            }
            $columns = implode(", ", $columns);
            $this->query("UPDATE \"comments\" SET {$columns} WHERE \"id\"=:id", $parameters);
            $this->bindValue(":id", $comment->id);
            foreach ($parameters as $parameter) {
                $this->bindValue($parameter["name"], $parameter["value"]);
            }
            $this->execute();
        } else {
            return null;
        }
    }

    public function delete(\Models\CommentModel $comment)
    {
        $this->query("DELETE FROM \"comments\"  WHERE \"id\"=:id");
        $this->bindValue(":id", $comment->id);
        $row = $this->execute();
        if ($this->rowCount() == 0) {
            return true;
        } else {
            return null;
        }
    }
}
