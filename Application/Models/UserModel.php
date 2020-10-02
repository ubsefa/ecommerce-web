<?php

namespace Models;

class UserModel
{
    use AuditModel;

    public $identityNumber;
    public $photo;
    public $nameLastName;
    public $userName;
    public $email;
    public $password;
    public $gender;
    public $birthDate;
    public $mobile;
    public $admin;
    public $roles;    
    public $active;
}