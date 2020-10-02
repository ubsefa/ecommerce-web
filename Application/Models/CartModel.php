<?php

namespace Models;

class CartModel
{   
    use AuditModel;
    
    public $order;
    public $product;
    public $quantity;
    public $price;
    public $tax;
    public $discount;
    public $features;
}
