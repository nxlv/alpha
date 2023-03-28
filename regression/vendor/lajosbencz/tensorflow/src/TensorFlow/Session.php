<?php


namespace TensorFlow;


use FFI;

final class Session extends API {
    private $c;
    private $graph;
    private $options;
    private $status;

    public function __construct(Graph $graph, SessionOptions $options = null, Status $status = null, $c_session = null) {
        $this->graph = $graph;
        if (is_null($options)) {
            $options = new SessionOptions();
        }
        $this->options = $options;
        if (is_null($status)) {
            $status = new Status();
        }
        $this->status = $status;
        if (!is_null($c_session)) {
            $this->c = $c_session;
        } else {
            $this->c = self::$ffi->TF_NewSession($this->graph->c, $this->options->c, $this->status->c);
            if ($this->status->code() != API::OK) {
                throw new \Exception($this->status->error());
            }
        }
    }

    public function __destruct() {
        $this->close();
    }

    public function devices() {
        $ret = [];
        $list = self::$ffi->TF_SessionListDevices($this->c, $this->status->c);
        if ($this->status->code() != API::OK) {
            throw new \Exception($this->status->error());
        }
        $count = self::$ffi->TF_DeviceListCount($list);
        for ($i = 0; $i < $count; $i++) {
            $name = self::$ffi->TF_DeviceListName($list, $i, $this->status->c);
            if ($this->status->code() != API::OK) {
                throw new \Exception($this->status->error());
            }
            $type = self::$ffi->TF_DeviceListType($list, $i, $this->status->c);
            if ($this->status->code() != API::OK) {
                throw new \Exception($this->status->error());
            }
            $mem = self::$ffi->TF_DeviceListMemoryBytes($list, $i, $this->status->c);
            if ($this->status->code() != API::OK) {
                throw new \Exception($this->status->error());
            }
            $dev = new \stdClass(); //??
            $dev->name = $name;
            $dev->type = $type;
            $dev->mem = $mem;
            $ret[] = $dev;
        }
        self::$ffi->TF_DeleteDeviceList($list);
        return $ret;
    }

    public function close() {
        if (!is_null($this->c)) {
            self::$ffi->TF_CloseSession($this->c, $this->status->c);
            if ($this->status->code() != API::OK) {
                throw new \Exception($this->status->error());
            }
            self::$ffi->TF_DeleteSession($this->c, $this->status->c);
            $this->c = null;
        }
    }

    public function run($fetches = null, array $feeds = null, $targes = null) {
        $n_fetches = 0;
        $c_fetches = null;
        $c_fetchTensors = null;
        if (!is_null($fetches)) {
            if (is_array($fetches)) {
                $n_fetches = count($fetches);
                if ($n_fetches > 0) {
                    $c_fetches = self::$ffi->new("TF_Output[$n_fetches]");
                    $t_fetchTensors = FFI::arrayType(self::$tensor_ptr, [$n_fetches]);
                    $c_fetchTensors = self::$ffi->new($t_fetchTensors);
                }
                $i = 0;
                foreach ($fetches as $fetch) {
                    $c_fetches[$i] = $fetch->c;
                    $i++;
                }
            } else {
                $n_fetches = 1;
                $c_fetches = self::$ffi->new("TF_Output[1]");
                $t_fetchTensors = FFI::arrayType(self::$tensor_ptr, [$n_fetches]);
                $c_fetchTensors = self::$ffi->new($t_fetchTensors);
                $c_fetches[0] = $fetches->c;
            }
        }

        $n_feeds = 0;
        $c_feeds = null;
        $c_feedTensors = null;
        if (is_array($feeds)) {
            $n_feeds = count($feeds);
            if ($n_feeds > 0) {
                $c_feeds = self::$ffi->new("TF_Output[$n_feeds]");
                $c_feedTensors = self::$ffi->new("TF_Tensor*[$n_feeds]");
                $i = 0;
                foreach ($feeds as $key => $val) {
                    $op = $this->graph->operation($key);
                    if (!is_null($op)) {
                        $feed = new Output($this->graph);
                        $feed->init($op, 0);
                        $c_feeds[$i] = $feed->c;
                        $c_feedTensors[$i] = $val->c;
                        $i++;
                    } else {
                        --$n_feeds;
                    }
                }
            }
        }

        $n_targets = 0;
        $c_targets = null;
        // TODO: process $targets ???

        self::$ffi->TF_SessionRun($this->c, null,
            $c_feeds, $c_feedTensors, $n_feeds, // Inputs
            $c_fetches, $c_fetchTensors, $n_fetches, // Outputs
            $c_targets, $n_targets, // Operations
            null, $this->status->c);

        if ($this->status->code() != API::OK) {
            throw new \Exception($this->status->error());
        }

        if (is_array($fetches)) {
            $ret = array();
            for ($i = 0; $i < $n_fetches; $i++) {
                $t = new Tensor();
                $t->initFromC($c_fetchTensors[$i]);
                $ret[$i] = $t;
            }
            return $ret;
        } else if (!is_null($fetches)) {
            $t = new Tensor();
            $t->initFromC($c_fetchTensors[0]);
            return $t;
        }
    }

}

