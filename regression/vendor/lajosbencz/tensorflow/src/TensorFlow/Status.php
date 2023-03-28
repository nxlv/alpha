<?php


namespace TensorFlow;


final class Status extends API {
    public $c;

    public function __construct() {
        $this->c = self::$ffi->TF_NewStatus();
    }

    public function __destruct() {
        self::$ffi->TF_DeleteStatus($this->c);
    }

    public function code() {
        return (int)self::$ffi->TF_GetCode($this->c);
    }

    public function string() {
        return (string)self::$ffi->TF_Message($this->c);
    }

    public function error() {
        return $this->string();
    }
}
