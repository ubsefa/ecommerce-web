<?php

namespace Models;

class CommentModel
{
    use AuditModel;

    public $product;
    public $comment;
    public $active;
}