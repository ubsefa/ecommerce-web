<?php

namespace Models;

class ProductModel
{
    use AuditModel;

    public $sku;
    public $title;
    public $keywords;
    public $description;
    public $price;
    public $tax;
    public $discount;
    public $image;
    public $languageCode;
    public $active;
}
