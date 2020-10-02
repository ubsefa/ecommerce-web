<?php

namespace Models;

class ContentModel
{
    use AuditModel;

    public $title;
    public $abstract;   
    public $content;
    public $image;
    public $languageCode;
    public $active;
}
