<?php

namespace Repositories;

class AuthRepository extends \Libraries\Database
{
    public function signUp(\Models\UserModel $user): ?\Models\UserModel
    {
        $userRepository = new UserRepository;
        return $userRepository->add($user);
    }

    public function signIn($userName, $password): ?\Models\UserModel
    {
        $this->query("SELECT * FROM \"users\" WHERE \"userName\"=:userName AND \"active\"=:active");
        $this->bindValue(":userName", $userName);
        $this->bindValue(":active", "b1");
        $row = $this->fetch();
        if ($this->rowCount() > 0) {
            if (password_verify($password, $row->password)) {
                $userRepository = new UserRepository;
                return $userRepository->getById($row->id);
            } else {
                return null;
            }
        } else {
            return null;
        }
    }

    public function forgotPassword($email): ?\Models\UserModel
    {
        $this->query("SELECT * FROM \"users\" WHERE \"email\"=:email AND \"active\"=:active");
        $this->bindValue(":email", $email);
        $this->bindValue(":active", "b1");
        $row = $this->fetch();
        if ($this->rowCount() > 0) {
            $userRepository = new UserRepository;
            return $userRepository->getById($row->id);
        } else {
            return null;
        }
    }
}
