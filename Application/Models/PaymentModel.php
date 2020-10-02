<?php

namespace Models;

class PaymentModel
{
    use AuditModel;
    
    public $order;
    public $paymentType;
    public $allowed;
}
