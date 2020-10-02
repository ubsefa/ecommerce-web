<?php

namespace Models;

class ShipmentModel
{
    use AuditModel;
    
    public $shipping;
    public $cart;
    public $trackingNumber;
    public $carrier;
    public $comments;
    public $active;
}