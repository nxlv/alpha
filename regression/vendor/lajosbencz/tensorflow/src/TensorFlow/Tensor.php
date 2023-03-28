<?php


namespace TensorFlow;

use FFI;

final class Tensor extends API {
    public $c;
    private $dataType;
    private $ndims;
    private $shape;
    private $nflattened;
    private $dataSize;
    private $status;

    public function init($value, $dataType = null, $shape = null, $status = null) {
        if (is_null($status)) {
            $status = new Status();
        }
        $this->status = $status;

        if (!is_null($value)) {
            if ($dataType == null) {
                $dataType = self::_guessType($value);
            }
            if ($shape == null) {
                $shape = self::_guessShape($value);
            }
        }

        $ndims = 0;
        $shapePtr = null;
        $nflattened = 1;
        if (is_array($shape)) {
            $ndims = count($shape);
            if ($ndims > 0) {
                $shapePtr = self::$ffi->new("int64_t[$ndims]");
                $i = 0;
                foreach ($shape as $val) {
                    $shapePtr[$i] = $val;
                    $nflattened *= $val;
                    $i++;
                }
            }
        }
        if ($dataType == API::STRING) {
            $nbytes = $nflattened * 8 + self::_byteSizeOfEncodedStrings($value);
        } else {
            $nbytes = self::$ffi->TF_DataTypeSize($dataType) * $nflattened;
        }

        $this->c = self::$ffi->TF_AllocateTensor($dataType, $shapePtr, $ndims, $nbytes);
        $this->dataType = $dataType;
        $this->shape = $shape;
        $this->ndims = $ndims;
        $this->nflattened = $nflattened;
        $this->dataSize = $nbytes;

        if (!is_null($value)) {
            $data = $this->data();
            if ($dataType == API::STRING) {
                $this->_stringEncode($value, $data);
            } else {
                $this->_encode($value, $data);
            }
        }
    }

    public function initFromC($cdata) {
        if (is_null($this->status)) {
            $this->status = new Status();
        }

        $this->c = $cdata;
        $this->dataType = self::$ffi->TF_TensorType($cdata);
        $ndims = self::$ffi->TF_NumDims($cdata);
        $this->ndims = $ndims;
        $this->nflattened = 1;
        for ($i = 0; $i < $ndims; $i++) {
            $dim = self::$ffi->TF_Dim($cdata, $i);
            $this->shape[$i] = $dim;
            $this->nflattened *= $dim;
        }
        $this->dataSize = self::$ffi->TF_TensorByteSize($cdata);
    }

    public function __destruct() {
        if (!is_null($this->c)) {
            self::$ffi->TF_DeleteTensor($this->c);
        }
    }

    public function type() {
        return $this->dataType;
    }

    public function shape() {
        return $this->shape;
    }

    public function typeName() {
        return self::_typeName($this->dataType, $this->shape);
    }

    public function value() {
        $data = $this->data();
        if ($this->dataType == API::STRING) {
            return $this->_stringDecode($data);
        } else {
            return $this->_decode($data);
        }
    }

    public function isSerializable() {
        static $serializable = [
            API::FLOAT      => 1,
            API::DOUBLE     => 1,
            API::INT32      => 1,
            API::UINT8      => 1,
            API::INT16      => 1,
            API::INT8       => 1,
            API::COMPLEX64  => 1,
            API::COMPLEX    => 1,
            API::INT64      => 1,
            API::BOOL       => 1,
            API::QINT8      => 1,
            API::QUINT8     => 1,
            API::QINT32     => 1,
            API::BFLOAT16   => 1,
            API::QINT16     => 1,
            API::QUINT16    => 1,
            API::UINT16     => 1,
            API::COMPLEX128 => 1,
            API::HALF       => 1,
            API::UINT32     => 1,
            API::UINT64     => 1,
        ];
        return isset($serializable[$this->dataType]);
    }

    public function bytes() {
        if (!$this->isSerializable()) {
            throw new \Exception("Unserializable tensor");
        }
        return FFI::string($this->plainData(), $this->dataSize);
    }

    public function setBytes(string $str) {
        if (!$this->isSerializable()) {
            throw new \Exception("Unserializable tensor");
        }
        if (strlen($str) != $this->dataSize) {
            throw new \Exception("Size mismatch");
        }
        return FFI::memcpy($this->plainData(), $str, $this->dataSize);
    }

    public function plainData() {
        return self::$ffi->TF_TensorData($this->c);
    }

    public function data() {
        static $map = [
            API::FLOAT      => "float",
            API::DOUBLE     =>"double",
            API::INT32      => "int32_t",
            API::UINT8      => "uint8_t",
            API::INT16      => "int16_t",
            API::INT8       => "int8_t",
            API::COMPLEX64  => null,
            API::COMPLEX    => null,
            API::INT64      => "int64_t",
            API::BOOL       => "bool",
            API::QINT8      => null,
            API::QUINT8     => null,
            API::QINT32     => null,
            API::BFLOAT16   => null,
            API::QINT16     => null,
            API::QUINT16    => null,
            API::UINT16     => "uint16_t",
            API::COMPLEX128 => null,
            API::HALF       => null,
            API::RESOURCE   => null,
            API::VARIANT    => null,
            API::UINT32     => "uint32_t",
            API::UINT64     => "uint64_t",
        ];
        $n = $this->nflattened;
        if ($this->dataType == API::STRING) {
            $m = $this->dataSize - $this->nflattened * 8;
            return self::$ffi->cast(
                "struct {uint64_t offsets[$n]; char data[$m];}",
                $this->plainData());
        } else {
            $cast = @$map[$this->dataType];
            if (isset($cast)) {
                $cast .= "[$n]";
                return self::$ffi->cast($cast, $this->plainData());
            } else {
                throw new \Exception("Not Implemented"); //???
            }
        }
    }

    private function _stringEncode($value, $data, &$offset = 0, &$dim_offset = 0, $dim = 0, $n = 0) {
        if ($dim < $this->ndims) {
            $n = $this->shape[$dim];
            if (!is_array($value) || count($value) != $n) {
                throw new \Exception("value/shape mismatch");
            }
            $dim++;
            $i = 0;
            foreach ($value as $val) {
                $this->_stringEncode($val, $data, $offset, $dim_offset, $dim, $i);
                $i++;
            }
            return;
        }

        $str = (string)$value;
        $data->offsets[$dim_offset++] = $offset;
        $offset += self::$ffi->TF_StringEncode(
            $str,
            strlen($str),
            $data->data + $offset,
            self::$ffi->TF_StringEncodedSize(strlen($str)),
            $this->status->c);
        if ($this->status->code() != API::OK) {
            throw new \Exception($this->status->error());
        }
    }

    private function _stringDecode($data, &$dim_offset = 0, $dim = 0, $n = 0) {
        if ($dim < $this->ndims) {
            $n = $this->shape[$dim];
            $dim++;
            $ret = array();
            for ($i = 0; $i < $n; $i++) {
                $ret[$i] = $this->_stringDecode($data, $dim_offset, $dim, $i);
            }
            return $ret;
        }

        $offset = $data->offsets[$dim_offset++];

        $dst = self::$ffi->new("char*[1]");
        $dst_len = self::$ffi->new("size_t[1]");
        self::$ffi->TF_StringDecode(
            $data->data + $offset,
            $this->dataSize - $offset,
            $dst,
            $dst_len,
            $this->status->c);
        if ($this->status->code() != API::OK) {
            throw new \Exception($this->status->error());
        }
        return FFI::string($dst[0], $dst_len[0]);
    }

    private function _encode($value, $data, &$dim_offset = 0, $dim = 0, $n = 0) {
        if ($dim < $this->ndims) {
            $n = $this->shape[$dim];
            if (!is_array($value) || count($value) != $n) {
                throw new \Exception("value/shape mismatch");
            }
            $dim++;
            $i = 0;
            foreach ($value as $val) {
                $this->_encode($val, $data, $dim_offset, $dim, $i++);
            }
            return;
        }
        $data[$dim_offset++] = $value;
    }

    private function _decode($data, &$dim_offset = 0, $dim = 0, $n = 0) {
        if ($dim < $this->ndims) {
            $n = $this->shape[$dim];
            $dim++;
            $ret = array();
            for ($i = 0; $i < $n; $i++) {
                $ret[$i] = $this->_decode($data, $dim_offset, $dim, $i);
            }
            return $ret;
        }
        return $data[$dim_offset++];
    }

    private static function _guessType($value) {
        if (is_array($value)) {
            foreach($value as $val) {
                return self::_guessType($val);
            }
        }
        if (is_int($value)) {
            return PHP_INT_SIZE == 4 ? API::INT32 : API::INT64;
        } else if (is_double($value)) {
            return API::DOUBLE;
        } else if (is_bool($value)) {
            return API::BOOL;
        } else if (is_string($value)) {
            return API::STRING;
        } else {
            throw new \Exception("Cannot guess type");
        }
    }

    private static function _guessShape($value, array $shape = []) {
        if (is_array($value)) {
            $shape[] = count($value);
            foreach($value as $val) {
                return self::_guessShape($val, $shape);
            }
        }
        return $shape;
    }

    private static function _byteSizeOfEncodedStrings($value) {
        if (is_array($value)) {
            $size = 0;
            foreach($value as $val) {
                $size += self::_byteSizeOfEncodedStrings($val);
            }
            return $size;
        } else {
            $val = (string)$value;
            return self::$ffi->TF_StringEncodedSize(strlen($val));
        }
    }
}
