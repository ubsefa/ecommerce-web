<?php

namespace Libraries;

class Validation
{
    public $patterns = array(
        "uri" => "[A-Za-z0-9-\/_?&=]+",
        "url" => "[A-Za-z0-9-:.\/_?&=#]+",
        "alpha" => "[\p{L}]+",
        "words" => "[\p{L}\s]+",
        "alphanum" => "[\p{L}0-9]+",
        "int" => "[0-9]+",
        "float" => "[0-9\.,]+",
        "tel" => "[0-9+\s()-]+",
        "text" => "[\p{L}0-9\s-.,;:!\"%&()?+\'°#\/@]+",
        "file" => "[\p{L}\s0-9-_!%&()=\[\]#@,.;+]+\.[A-Za-z0-9]{2,4}",
        "folder" => "[\p{L}\s0-9-_!%&()=\[\]#@,.;+]+",
        "address" => "[\p{L}0-9\s.,()°-]+",
        "email" => "[a-zA-Z0-9_.-]+@[a-zA-Z0-9-]+.[a-zA-Z0-9-.]+[.]+[a-z-A-Z]",
        "dateDMY" => "[0-9]{1,2}\-[0-9]{1,2}\-[0-9]{4}",
        "dateYMD" => "[0-9]{4}\-[0-9]{1,2}\-[0-9]{1,2}",
    );

    public $errors = array();

    public function name($name)
    {
        $this->name = $name;
        return $this;
    }

    public function value($value)
    {
        $this->value = $value;
        return $this;
    }

    public function file($value)
    {
        $this->file = $value;
        return $this;
    }

    public function pattern($name, $message)
    {
        if ($name == "array") {
            if (!is_array($this->value)) {
                $this->errors[] = $message;
            }
        } else {
            $regex = "/^({$this->patterns[$name]})$/u";
            if ($this->value != "" && !preg_match($regex, $this->value)) {
                $this->errors[] = $message;
            }
        }
        return $this;
    }

    public function customPattern($pattern, $message)
    {
        $regex = "/^({$pattern})$/u";
        if ($this->value != "" && !preg_match($regex, $this->value)) {
            $this->errors[] = $message;
        }
        return $this;
    }

    public function required($message)
    {

        if ((isset($this->file) && $this->file["error"] == 4) || ($this->value == "" || $this->value == null)) {
            $this->errors[] = $message;
        }
        return $this;
    }

    public function min($length, $message)
    {
        if (is_string($this->value)) {
            if (strlen($this->value) < $length) {
                $this->errors[] = $message;
            }
        } else {
            if ($this->value < $length) {
                $this->errors[] = $message;
            }
        }
        return $this;
    }

    public function max($length, $message)
    {
        if (is_string($this->value)) {
            if (strlen($this->value) > $length) {
                $this->errors[] = $message;
            }
        } else {
            if ($this->value > $length) {
                $this->errors[] = $message;
            }
        }
        return $this;
    }

    public function equal($value, $message)
    {
        if ($this->value != $value) {
            $this->errors[] = $message;
        }
        return $this;
    }

    public function maxSize($size, $message)
    {
        if ($this->file["error"] != 4 && $this->file["size"] > $size) {
            $this->errors[] = sprintf($message, number_format($size / 1048576, 2));
        }
        return $this;
    }

    public function ext($extension, $message)
    {
        if ($this->file["error"] != 4 && pathinfo($this->file["name"], PATHINFO_EXTENSION) != $extension && strtoupper(pathinfo($this->file["name"], PATHINFO_EXTENSION)) != $extension) {
            $this->errors[] = sprintf($message, $extension);
        }
        return $this;
    }

    public function purify($string)
    {
        return htmlspecialchars($string, ENT_QUOTES, "UTF-8");
    }

    public function isSuccess()
    {
        if (empty($this->errors)) {
            return true;
        }
    }

    public function getErrors()
    {
        if (!$this->isSuccess()) {
            return $this->errors;
        }
    }

    public static function isInt($value)
    {
        if (filter_var($value, FILTER_VALIDATE_INT)) {
            return true;
        }
    }

    public static function isFloat($value)
    {
        if (filter_var($value, FILTER_VALIDATE_FLOAT)) {
            return true;
        }
    }

    public static function isAlpha($value)
    {
        if (filter_var($value, FILTER_VALIDATE_REGEXP, array("options" => array("regexp" => "/^[a-zA-Z]+$/")))) {
            return true;
        }
    }

    public static function isAlphanum($value)
    {
        if (filter_var($value, FILTER_VALIDATE_REGEXP, array("options" => array("regexp" => "/^[a-zA-Z0-9]+$/")))) {
            return true;
        }
    }

    public static function isUrl($value)
    {
        if (filter_var($value, FILTER_VALIDATE_URL)) {
            return true;
        }
    }

    public static function isUri($value)
    {
        if (filter_var($value, FILTER_VALIDATE_REGEXP, array("options" => array("regexp" => "/^[A-Za-z0-9-\/_]+$/")))) {
            return true;
        }
    }

    public static function isBool($value)
    {
        if (is_bool(filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE))) {
            return true;
        }
    }

    public static function isEMail($value)
    {
        if (filter_var($value, FILTER_VALIDATE_EMAIL)) {
            return true;
        }
    }
}


/*$email = 'example@email.com';
$username = 'admin';
$password = 'test';
$age = 29;

$val = new Validation();
$val->name('email')->value($email)->pattern('email')->required();
$val->name('username')->value($username)->pattern('alpha')->required();
$val->name('password')->value($password)->customPattern('[A-Za-z0-9-.;_!#@]{5,15}')->required();
$val->name('age')->value($age)->min(18)->max(40);

if($val->isSuccess()){
    echo "Validation ok!";
}else{
    echo "Validation error!";
    var_dump($val->getErrors());
}*/