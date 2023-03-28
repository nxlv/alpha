<?php


namespace TensorFlow;

use FFI;


final class Operation extends API {
    public $c;
    private $graph;

    public function __construct(Graph $graph) {
        $this->graph = $graph;
    }

    public function init($graph, $type, $name, array $input = [], array $control = [], array $attr = [], string $device = null) {
        $status = new Status();
        $desc = self::$ffi->TF_NewOperation($graph->c, $type, $name);

        foreach ($input as $in) {
            if ($in instanceof Output) {
                self::$ffi->TF_AddInput($desc, $in->c);
            } else if (is_array($in)) {
                $n_inputs = count($in);
                $c_inputs = self::$ffi->new("TF_Output[$n_inputs]");
                $i = 0;
                foreach ($in as $el) {
                    $c_inputs[$i] = $el->c;
                    $i++;
                }
                self::$ffi->TF_AddInputList($desc, $c_inputs, $n_inputs);
            }
        }

        foreach ($control as $ctl) {
            self::$ffi->TF_AddControlInput($desc, $ctl->c);
        }

        foreach ($attr as $key => $val) {
            if (is_string($val)) {
                self::$ffi->TF_SetAttrString($desc, $key, $val, strlen($val));
            } else if (is_int($val)) {
                self::$ffi->TF_SetAttrInt($desc, $key, $val);
            } else if (is_float($val)) {
                self::$ffi->TF_SetAttrFloat($desc, $key, $val);
            } else if (is_bool($val)) {
                self::$ffi->TF_SetAttrBool($desc, $key, $val);
            } else if (is_object($val) && $val instanceof Type) {
                self::$ffi->TF_SetAttrType($desc, $key, $val->type);
            } else if (is_object($val) && $val instanceof FuncName) {
                self::$ffi->TF_SetAttrFuncName($desc, $key, $val->func_name, strlen($val->func_name));
            } else if (is_object($val) && $val instanceof Shape) {
                $shape = $val->shape;
                $num_dims = count($shape);
                $dims = self::$ffi->new("int64_t[$num_dims]");
                $j = 0;
                foreach ($shape as $dim) {
                    $dims[$j++] = (int)$dim;
                }
                self::$ffi->TF_SetAttrShape($desc, $key, $dims, $num_dims);
            } else if (is_object($val) && $val instanceof Tensor) {
                self::$ffi->TF_SetAttrTensor($desc, $key, $val->c, $status->c);
                if ($status->code() != API::OK) {
                    throw new \Exception($status->error());
                }
            } else if (is_array($val) && count($val) > 0) {
                $num = count($val);
                foreach ($val as $el) break;
                if (is_string($el)) {
                    $buf = self::$ffi->new("char*[$num]");
                    $len = self::$ffi->new("size_t[$num]");
                    $i = 0;
                    foreach ($val as $el) {
                        if (is_string($el)) {
                            $buf[$i] = $el; //???
                            $len[$i] = strlen($el);
                            $i++;
                        } else {
                            throw new \Exception("Wrong attr type");
                        }
                    }
                    self::$ffi->TF_SetAttrStringList($desc, $key, $buf, $len, $num);
                } else if (is_int($el)) {
                    $buf = self::$ffi->new("int64_t[$num]");
                    $i = 0;
                    foreach ($val as $el) {
                        if (is_int($el)) {
                            $buf[$i++] = $el;
                        } else {
                            throw new \Exception("Wrong attr type");
                        }
                    }
                    self::$ffi->TF_SetAttrIntList($desc, $key, $buf, $num);
                } else if (is_float($el)) {
                    $buf = self::$ffi->new("float[$num]");
                    $i = 0;
                    foreach ($val as $el) {
                        if (is_float($el)) {
                            $buf[$i++] = $el;
                        } else {
                            throw new \Exception("Wrong attr type");
                        }
                    }
                    self::$ffi->TF_SetAttrFloatList($desc, $key, $buf, $num);
                } else if (is_bool($el)) {
                    $buf = self::$ffi->new("unsigned char[$num]");
                    $i = 0;
                    foreach ($val as $el) {
                        if (is_bool($el)) {
                            $buf[$i++] = $el;
                        } else {
                            throw new \Exception("Wrong attr type");
                        }
                    }
                    self::$ffi->TF_SetAttrBoolList($desc, $key, $buf, $num);
                } else if (is_object($el) && $el instanceof Type) {
                    $buf = self::$ffi->new("TF_DataType[$num]");
                    $i = 0;
                    foreach ($val as $el) {
                        if ($el instanceof Type) {
                            $buf[$i++] = $el->type;
                        } else {
                            throw new \Exception("Wrong attr type");
                        }
                    }
                    self::$ffi->TF_SetAttrTypeList($desc, $key, $buf, $num);
                } else if (is_object($el) && $el instanceof Shape) {
                    $buf = self::$ffi->new("int64_t*[$num]");
                    $len = self::$ffi->new("int[$num]");
                    $i = 0;
                    foreach ($val as $el) {
                        if ($el instanceof Shape) {
                            $shape = $el->shape;
                            $num_dims = count($shape);
                            $dims = self::$ffi->new("int64_t[$num_dims]");
                            $j = 0;
                            foreach ($shape as $dim) {
                                $dims[$j++] = (int)$dim;
                            }
                            $buf[$i] = $dims;
                            $len[$i] = $num_dims;
                            $i++;
                        } else {
                            throw new \Exception("Wrong attr type");
                        }
                    }
                    self::$ffi->TF_SetAttrShapeList($desc, $key, $buf, $len, $num);
                } else if (is_object($el) && $el instanceof Tensor) {
                    $buf = self::$ffi->new("TF_Tensor*[$num]");
                    $i = 0;
                    foreach ($val as $el) {
                        if ($el instanceof Tensor) {
                            $buf[$i++] = $el->type;
                        } else {
                            throw new \Exception("Wrong attr type");
                        }
                    }
                    self::$ffi->TF_SetAttrTensorList($desc, $key, $buf, $num, $status->c);
                    if ($status->code() != API::OK) {
                        throw new \Exception($status->error());
                    }
                } else {
                    throw new \Exception("Unknown Operation attr type");
                }
            } else {
                throw new \Exception("Unknown Operation attr type");
            }
        }

        if (is_string($device)) {
            self::$ffi->TF_SetDevice($desc, $device);
        } else if (!is_null($device)) {
            throw new \Exception("Wrong Operation device");
        }

        $this->c = self::$ffi->TF_FinishOperation($desc, $status->c);
        if ($status->code() != API::OK) {
            throw new \Exception($status->error());
        }
    }

    public function initFromC($cdata) {
        $this->c = $cdata;
    }

    public function name() {
        return (string)self::$ffi->TF_OperationName($this->c);
    }

    public function type() {
        return (string)self::$ffi->TF_OperationOpType($this->c);
    }

    public function device() {
        return (string)self::$ffi->TF_OperationDevice($this->c);
    }

    public function numInputs() {
        return (int)self::$ffi->TF_OperationNumInputs($this->c);
    }

    public function numOutputs() {
        return (int)self::$ffi->TF_OperationNumOutputs($this->c);
    }

    public function inputListSize($name) {
        $status = new Status();
        $ret = (int)self::$ffi->TF_OperationInputListLength($this->c, $name, $status->c);
        if ($status->code() != API::OK) {
            throw new \Exception($status->error());
        }
        return $ret;
    }

    public function outputListSize($name) {
        $status = new Status();
        $ret = (int)self::$ffi->TF_OperationOutputListLength($this->c, $name, $status->c);
        if ($status->code() != API::OK) {
            throw new \Exception($status->error());
        }
        return $ret;
    }

    public function input($n) {
        $input = new Input($this->graph);
        $input->init($this, $n);
        return $input;
    }

    public function output($n) {
        $output = new Output($this->graph);
        $output->init($this, $n);
        return $output;
    }

    public function numControlInputs() {
        return (int)self::$ffi->TF_OperationNumControlInputs($this->c);
    }

    public function controlInputs() {
        $num = $this->numControlInputs();
        if ($num) {
            $type = FFI::arrayType(self::$operation_ptr, [$num]);
            $buf = self::$ffi->new($type);
            $num = self::$ffi->TF_OperationGetControlInputs($this->c, $buf, $num);
            if ($num) {
                $ret = [];
                for ($i = 0; $i < $num; $i++) {
                    $in = new Operation($this->graph);
                    $in->initFromC(clone $buf[$i]);
                    $ret[] = $in;
                }
                return $ret;
            }
        }
        return [];
    }

    public function numControlOutputs() {
        return (int)self::$ffi->TF_OperationNumControlOutputs($this->c);
    }

    public function controlOutputs() {
        $num = $this->numControlOutputs();
        if ($num) {
            $type = FFI::arrayType(self::$operation_ptr, [$num]);
            $buf = self::$ffi->new($type);
            $num = self::$ffi->TF_OperationGetControlOutputs($this->c, $buf, $num);
            if ($num) {
                $ret = [];
                for ($i = 0; $i < $num; $i++) {
                    $in = new Operation($this->graph);
                    $in->initFromC(clone $buf[$i]);
                    $ret[] = $in;
                }
                return $ret;
            }
        }
        return [];
    }

}
