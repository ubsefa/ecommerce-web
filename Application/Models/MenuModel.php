<?php

namespace Models;

class MenuModel
{
    use AuditModel;

    public $parent;
    public $position;
    public $title;
    public $mega;
    public $sort;
    public $languageCode;
    public $active;
}
