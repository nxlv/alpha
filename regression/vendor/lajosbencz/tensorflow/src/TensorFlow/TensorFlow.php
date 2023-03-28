<?php


namespace TensorFlow;

use FFI;

final class TensorFlow extends API {
    public $graph;
    private $status;

    public function __construct() {
        if (is_null(self::$ffi)) self::init_tf_ffi();
        $this->_defaultGraph();
    }

    public function loadSavedModel(string $dir, array $tags = ["serve"], SessionOptions $options = null) {
        if (is_null($options)) {
            $options = new SessionOptions();
        }
        $n_tags = count($tags);
        $c_tags = self::$ffi->new("char*[$n_tags]");
        $i = 0;
        foreach ($tags as $tag) {
            $len = strlen($tag);
            $c_len = $len + 1;
            $str = self::$ffi->new("char[$c_len]", false);
            FFI::memcpy($str, $tag, $len);
            $c_tags[$i] = $str;
            $i++;
        }
        $graph = $this->_defaultGraph();
        $status = $this->_defaultStatus();
        $c_session = self::$ffi->TF_LoadSessionFromSavedModel(
            $options->c,
            null, // const TF_Buffer* run_options,
            $dir,
            $c_tags,
            $n_tags,
            $graph->c,
            null, // TF_Buffer* meta_graph_def,
            $status->c);
        for ($i = 0; $i < $n_tags; $i++) {
            FFI::free($c_tags[$i]);
        }
        if ($status->code() != API::OK) {
            throw new \Exception($status->error());
        }
        return new Session($graph, $options, $status, $c_session);
    }

    public function tensor($value, $dataType = null, $shape = null) {
        $status = $this->_defaultStatus();
        $tensor = new Tensor();
        $tensor->init($value, $dataType, $shape, $status);
        return $tensor;
    }

    public function op($type, array $input = [], array $control = [], array $attr = [], $name = null, $n = 0) {
        $graph = $this->_defaultGraph();
        $op = $graph->addOperation($type, $name, $input, $control, $attr);
        return $op->output($n);
    }

    public function constant($value, $dataType = null, $shape = null, $name = null) {
        $status = $this->_defaultStatus();
        $tensor = new Tensor();
        $tensor->init($value, $dataType, $shape, $status);
        return $this->op("Const", [], [], [
            "dtype" => new Type($tensor->type()),
            "value" => $tensor,
        ], $name);
    }

    public function placeholder($name, $dataType) {
        return $this->op("Placeholder", [], [], [
            "dtype" => new Type($dataType)
        ], $name);
    }

    public function add($x, $y, $name = null) {
        return $this->op("Add", [$x, $y], [], [], $name);
    }

    public function session() {
        $graph = $this->_defaultGraph();
        $status = $this->_defaultStatus();
        return new Session($graph, null, $this->status);
    }

    protected function _defaultGraph() {
        if (!isset($this->graph)) {
            $this->graph = new Graph();
        }
        return $this->graph;
    }

    protected function _defaultStatus() {
        if (!isset($this->status)) {
            $this->status = new Status();
        }
        return $this->status;
    }
}
