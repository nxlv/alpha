<?php


namespace TensorFlow;


final class FuncName {
    public $func_name;

    function __construct(string $func_name) {
        $this->shape_proto = $func_name;
    }
}
