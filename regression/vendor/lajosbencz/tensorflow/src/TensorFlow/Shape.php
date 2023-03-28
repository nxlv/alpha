<?php


namespace TensorFlow;


final class Shape {
    public $shape;

    function __construct(array $shape = null) {
        $this->shape = $shape;
    }
}
