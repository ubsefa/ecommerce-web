<?php

namespace Models;

class ShippingModel
{
    use AuditModel;
    
    public $title;
    public $email;
    public $address;
    public $city;
    public $state;
    public $postalCode;
    public $country;
    public $phone;
}