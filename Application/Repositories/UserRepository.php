<?php

namespace Repositories;

class UserRepository extends \Libraries\Database
{
    public function getById($id): ?\Models\UserModel
    {
        $this->query("SELECT * FROM \"users\" WHERE \"id\"=:id");
        $this->bindValue(":id", $id);
        $row = $this->fetch();
        if ($this->rowCount() > 0) {
            $user = new \Models\UserModel;
            foreach ($row as $property => $value) {
                if ($property == "roles") {
                    if ($row->admin) {
                        foreach (explode(",", $row->roles) as $role) {
                            if (array_key_exists($role, USER_ADMIN_ROLES) != "") {
                                $user->roles[$role] = USER_ADMIN_ROLES[$role];
                            }
                        }
                    } else {
                        foreach (explode(",", $row->roles) as $role) {
                            if (array_key_exists($role, USER_FRONT_ROLES) != "") {
                                $user->roles[$role] = USER_FRONT_ROLES[$role];
                            }
                        }
                    }
                } else {
                    $user->$property = $value;
                }
            }
            unset($user->password);
            return $user;
        } else {
            return null;
        }
    }

    public function getList($where, $orderBy, $limit, $offset, $parameters = array())
    {
        $this->query("SELECT * FROM \"users\" {$where} {$orderBy} {$limit} {$offset}", $parameters);
        $rows = $this->fetchAll();
        $users = array();
        foreach ($rows as $row) {
            $user = array();
            foreach ($row as $property => $value) {
                if ($property == "roles") {
                    if ($row->admin) {
                        foreach (explode(",", $row->roles) as $role) {
                            if (array_key_exists($role, USER_ADMIN_ROLES) != "") {
                                $user["roles"][$role] = USER_ADMIN_ROLES[$role];
                            }
                        }
                    } else {
                        foreach (explode(",", $row->roles) as $role) {
                            if (array_key_exists($role, USER_FRONT_ROLES) != "") {
                                $user["roles"][$role] = USER_FRONT_ROLES[$role];
                            }
                        }
                    }
                } else {
                    $user[$property] = $value;
                }
            }
            unset($user->password);
            array_push($users, (object) $user);
        }
        return $users;
    }

    public function add(\Models\UserModel $user): ?\Models\UserModel
    {
        $this->query("SELECT * FROM \"users\" WHERE LOWER(\"identityNumber\")=:identityNumber OR LOWER(\"userName\")=:userName OR LOWER(\"email\")=:email OR LOWER(\"mobile\")=:mobile");
        $this->bindValue(":identityNumber", strtolower($user->identityNumber));
        $this->bindValue(":userName", strtolower($user->userName));
        $this->bindValue(":email", strtolower($user->email));
        $this->bindValue(":mobile", $user->mobile);
        $row = $this->fetch();
        if ($this->rowCount() == 0) {
            $columns = array();
            $values = array();
            $parameters = array();
            foreach ($user as $property => $value) {
                if ($property != "id" && !is_null($value)) {
                    array_push($columns, "\"{$property}\"");
                    array_push($values, ":{$property}");
                    array_push($parameters, array("name" => ":{$property}", "value" => ($property == "password" && !empty($value) ? password_hash($value, PASSWORD_DEFAULT) : $value)));
                }
            }
            $columns = implode(", ", $columns);
            $values = implode(", ", $values);
            $this->query("INSERT INTO \"users\" ({$columns}) VALUES ({$values})", $parameters);
            $this->execute();
            return $this->getById($this->lastInsertId());
        } else {
            return null;
        }
    }

    public function update(\Models\UserModel $user): ?\Models\UserModel
    {
        $this->query("SELECT * FROM \"users\" WHERE \"id\"!=:id AND (LOWER(\"identityNumber\")=:identityNumber OR LOWER(\"userName\")=:userName OR LOWER(\"email\")=:email OR LOWER(\"mobile\")=:mobile)");
        $this->bindValue(":id", $user->id);
        $this->bindValue(":identityNumber", strtolower($user->identityNumber));
        $this->bindValue(":userName", strtolower($user->userName));
        $this->bindValue(":email", strtolower($user->email));
        $this->bindValue(":mobile", $user->mobile);
        $row = $this->fetch();
        if ($this->rowCount() == 0) {
            $columns = array();
            $parameters = array();
            foreach ($user as $property => $value) {
                if ($property != "id" && !is_null($value)) {
                    array_push($columns, "\"{$property}\"=:{$property}");
                    array_push($parameters, array("name" => ":{$property}", "value" => $property == "password" && !empty($value) ? password_hash($value, PASSWORD_DEFAULT) : $value));
                }
            }
            $columns = implode(", ", $columns);
            $this->query("UPDATE \"users\" SET {$columns} WHERE \"id\"=:id", $parameters);
            $this->bindValue(":id", $user->id);
            foreach ($parameters as $parameter) {
                $this->bindValue($parameter["name"], $parameter["value"]);
            }
            $this->execute();
        } else {
            return null;
        }
    }

    public function delete(\Models\UserModel $user)
    {
        $this->query("DELETE FROM \"users\"  WHERE \"id\"=:id");
        $this->bindValue(":id", $user->id);
        $row = $this->execute();
        if ($this->rowCount() == 0) {
            return true;
        } else {
            return null;
        }
    }
}
