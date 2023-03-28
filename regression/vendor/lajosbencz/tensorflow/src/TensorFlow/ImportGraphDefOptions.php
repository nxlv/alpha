<?php


namespace TensorFlow;


final class ImportGraphDefOptions extends API {
    public $c;

    public function __construct() {
        $this->c = self::$ffi->TF_NewImportGraphDefOptions();
    }

    public function __destruct() {
        self::$ffi->TF_DeleteImportGraphDefOptions($this->c);
    }

    public function setPrefix(string $prefix) {
        self::$ffi->TF_ImportGraphDefOptionsSetPrefix($this->c, $prefix);
    }
}
