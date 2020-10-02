<?php

namespace Models;

class SettingModel
{
    use AuditModel;

    public $title;
    public $keywords;
    public $desription;
    public $logo; 
    public $email;
    public $address;
    public $city;
    public $state;
    public $postalCode;
    public $country;
    public $phone;
    public $features;
    public $maintenance;
}