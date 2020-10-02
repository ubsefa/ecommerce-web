<?php

namespace Models;

trait AuditModel
{
    public $id;
    public $createdAt;
    public $creator;
    public $creatorIP;
    public $updatedAt;
    public $updater;
    public $updaterIP;
}
