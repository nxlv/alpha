<?php


namespace TensorFlow;


final class SessionOptions extends API {
    public $c;

    public function __construct() {
        $this->c = self::$ffi->TF_NewSessionOptions();
    }

    public function __destruct() {
        self::$ffi->TF_DeleteSessionOptions($this->c);
    }

    public static function setTarget() {
        throw new \Exception("Not Implemented"); //???
    }

    public static function setConfig() {
        throw new \Exception("Not Implemented"); //???
    }
}
