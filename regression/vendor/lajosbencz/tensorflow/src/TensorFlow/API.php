<?php


namespace TensorFlow;

use FFI;

class API
{
    const FLOAT      = 1;
    const DOUBLE     = 2;
    const INT32      = 3;
    const UINT8      = 4;
    const INT16      = 5;
    const INT8       = 6;
    const STRING     = 7;
    const COMPLEX64  = 8;
    const COMPLEX    = 8;
    const INT64      = 9;
    const BOOL       = 10;
    const QINT8      = 11;
    const QUINT8     = 12;
    const QINT32     = 13;
    const BFLOAT16   = 14;
    const QINT16     = 15;
    const QUINT16    = 16;
    const UINT16     = 17;
    const COMPLEX128 = 18;
    const HALF       = 19;
    const RESOURCE   = 20;
    const VARIANT    = 21;
    const UINT32     = 22;
    const UINT64     = 23;

    const TYPE_NAME  = [
        self::FLOAT      => "FLOAT",
        self::DOUBLE     => "DOUBLE",
        self::INT32      => "INT32",
        self::UINT8      => "UINT8",
        self::INT16      => "INT16",
        self::INT8       => "INT8",
        self::STRING     => "STRING",
        self::COMPLEX64  => "COMPLEX64",
        self::COMPLEX    => "COMPLEX",
        self::INT64      => "INT64",
        self::BOOL       => "BOOL",
        self::QINT8      => "QINT8",
        self::QUINT8     => "QUINT8",
        self::QINT32     => "QINT32",
        self::BFLOAT16   => "BFLOAT16",
        self::QINT16     => "QINT16",
        self::QUINT16    => "QUINT16",
        self::UINT16     => "UINT16",
        self::COMPLEX128 => "COMPLEX128",
        self::HALF       => "HALF",
        self::RESOURCE   => "RESOURCE",
        self::VARIANT    => "VARIANT",
        self::UINT32     => "UINT32",
        self::UINT64     => "UINT64",
    ];

    const OK                  = 0;
    const CANCELLED           = 1;
    const UNKNOWN             = 2;
    const INVALID_ARGUMENT    = 3;
    const DEADLINE_EXCEEDED   = 4;
    const NOT_FOUND           = 5;
    const ALREADY_EXISTS      = 6;
    const PERMISSION_DENIED   = 7;
    const UNAUTHENTICATED     = 16;
    const RESOURCE_EXHAUSTED  = 8;
    const FAILED_PRECONDITION = 9;
    const ABORTED             = 10;
    const OUT_OF_RANGE        = 11;
    const UNIMPLEMENTED       = 12;
    const INTERNAL            = 13;
    const UNAVAILABLE         = 14;
    const DATA_LOSS           = 15;

    static protected $ffi;
    static protected $tensor_ptr;
    static protected $operation_ptr;

    static protected function init_tf_ffi() {
        self::$ffi = FFI::load(__DIR__ . "/../../tf_api.h");
        self::$tensor_ptr = self::$ffi->type("TF_Tensor*");
        self::$operation_ptr = self::$ffi->type("TF_Operation*");
    }

    public function version() {
        return (string)self::$ffi->TF_Version();
    }

    static protected function _typeName($type, $shape) {
        if ($type < 100) {
            $name = self::TYPE_NAME[$type];
        } else {
            $name = '&' . self::TYPE_NAME[$type - 100];
        }
        if (is_array($shape) && count($shape) > 0) {
            $name .= '[' . implode(',', $shape) . ']';
        }
        return $name;
    }
}
