<?php


namespace TensorFlow;


use FFI;

final class Buffer extends API {
    public $c;

    public function __construct($str = null) {
        if (is_null($str)) {
            $this->c  = self::$ffi->TF_NewBuffer();
        } else if (is_object($str) &&
            $str instanceof FFI\CData &&
            self::$ffi->type($str) == self::$ffi->type("TF_Buffer*")) {
            $this->c = $str;
        } else {
            $this->c = self::$ffi->TF_NewBufferFromString($str, strlen($str));
        }
    }

    public function __destruct() {
        self::$ffi->TF_DeleteBuffer($this->c);
    }

    public function string() {
        return FFI::string($this->c[0]->data, $this->c[0]->length);
    }
}
