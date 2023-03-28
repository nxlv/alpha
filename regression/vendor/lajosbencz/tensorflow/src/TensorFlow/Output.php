<?php


namespace TensorFlow;


final class Output extends API {
    public $c;
    private $graph;

    public function __construct(Graph $graph) {
        $this->graph = $graph;
    }

    public function init(Operation $operation, int $index) {
        $this->c = self::$ffi->new("TF_Output");
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
        return (int)self::$ffi->TF_OperationOutputType($this->c);
    }

    public function shape() {
        $status = new Status;
        $ndims = self::$ffi->TF_GraphGetTensorNumDims($this->graph->c, $this->c, $status->c);
        if ($status->code() != API::OK) {
            throw new \Exception($status->error());
        }
        $ret = null;
        if ($ndims >= 0) {
            $buf = self::$ffi->new("int64_t[$ndims]");
            self::$ffi->TF_GraphGetTensorShape($this->graph->c, $this->c,
                $buf, $ndims, $status->c);
            if ($status->code() != API::OK) {
                throw new \Exception($status->error());
            }
            $ret = [];
            for ($i = 0; $i < $ndims; $i++) {
                $ret[$i] = $buf[$i];
            }
        }
        return $ret;
    }

    public function typeName() {
        return self::_typeName($this->type(), $this->shape());
    }

    public function numConsumers() {
        return self::$ffi->TF_OperationOutputNumConsumers($this->c);
    }

    public function consumers() {
        $num = self::$ffi->TF_OperationOutputNumConsumers($this->c);
        if ($num) {
            $buf = self::$ffi->new("TF_Input[$num]");
            $num = self::$ffi->TF_OperationOutputConsumers($this->c, $buf, $num);
            if ($num) {
                $ret = [];
                for ($i = 0; $i < $num; $i++) {
                    $in = new Input($this->graph);
                    $in->initFromC(clone $buf[$i]);
                    $ret[] = $in;
                }
                return $ret;
            }
        }
        return [];
    }
}
