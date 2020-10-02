<?php

namespace Models;

class AddressModel
{
    use AuditModel;

    public $title;
    public $nameLastName;
    public $address;
    public $city;
    public $state;
    public $postalCode;
    public $country;
    public $phone;
    public $active;
}