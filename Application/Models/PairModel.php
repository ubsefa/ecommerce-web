<?php

namespace Models;

class PairModel
{
    use AuditModel;

    public $type;
    public $object;
    public $key;
    public $value;
}