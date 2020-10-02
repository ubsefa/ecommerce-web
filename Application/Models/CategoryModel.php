<?php

namespace Models;

class CategoryModel
{
    use AuditModel;

    public $parent;
    public $title;
    public $keywords;
    public $description;
    public $image;
    public $languageCode;
    public $active;
}