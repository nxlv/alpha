<?php


namespace TensorFlow;


final class Input extends API {
    public $c;
    private $graph;

    public function __construct(Graph $graph) {
        $this->graph = $graph;
    }

    public function init(Operation $operation, int $index) {
        $this->c = self::$ffi->new("TF_Input");
        $this->c->oper = $operation->c;
        $this->c->index = $index;
    }

    public function initFromC($cdata) {
        $this->c = $cdata;
    }

    public function op() {
        $op = new Operation($this->graph);
        $op->initFromC($this->c->oper);
        return $op;
    }

    public function index() {
        return $this->c->index;
    }

    public function type() {
        return (int)self::$ffi->TF_OperationInputType($this->c);
    }

    public function shape() {
        $producer = $this->producer();
        return $producer->shape();
    }

    public function typeName() {
        return self::_typeName($this->type(), $this->shape());
    }

    public function producer() {
        $cdata = self::$ffi->TF_OperationInput($this->c);
        $output = new Output($this->graph);
        $output->initFromC($cdata);
        return $output;
    }
}
