<?php

declare(strict_types=1);

namespace Paxal\DBus;

use FFI;
use PaxalDbusPhp;
use function array_shift;
use function array_values;
use function assert;
use function ord;

final class DBusMessageIterDecoder
{
    /** @var PaxalDbusPhp|FFI */
    private $ffi;

    /**
     * @param PaxalDbusPhp|FFI $ffi
     */
    public function __construct($ffi)
    {
        $this->ffi = $ffi;
    }

    /**
     * @return mixed[]
     */
    public function decode(FFI\CData $args) : array
    {
        assert($this->ffi instanceof PaxalDbusPhp);
        $result = [];
        $i      = 0;
        do {
            $type = $this->ffi->dbus_message_iter_get_arg_type(FFI::addr($args));
            switch ($type) {
                case DBus::TYPE_STRING:
                case DBus::TYPE_SIGNATURE:
                case DBus::TYPE_OBJECT_PATH:
                    $w = FFI::new('char*');
                    $this->ffi->dbus_message_iter_get_basic(FFI::addr($args), FFI::addr($w));
                    $result[] = FFI::string($w);
                    break;
                case DBus::TYPE_BOOLEAN:
                    $w = FFI::new('bool');
                    $this->ffi->dbus_message_iter_get_basic(FFI::addr($args), FFI::addr($w));
                    $result[] = $w->cdata;
                    break;
                case DBus::TYPE_INT16:
                    $result[] = $this->cdata('short', $args);
                    break;

                case DBus::TYPE_UINT16:
                    $result[] = $this->cdata('unsigned short', $args);
                    break;
                case DBus::TYPE_INT32:
                    $result[] = $this->cdata('long', $args);
                    break;
                case DBus::TYPE_UINT32:
                    $result[] = $this->cdata('unsigned long', $args);
                    break;
                case DBus::TYPE_INT64:
                    $result[] = $this->cdata('int', $args);
                    break;
                case DBus::TYPE_UINT64:
                    $result[] = $this->cdata('unsigned int', $args);
                    break;
                case DBus::TYPE_DOUBLE:
                    $result[] = $this->cdata('double', $args);
                    break;

                case DBus::TYPE_BYTE:
                    $result[] = ord((string) $this->cdata('char', $args));
                    break;

                case DBus::TYPE_DICT_ENTRY:
                    $sub = $this->ffi->new('struct DBusMessageIter');
                    $this->ffi->dbus_message_iter_recurse(FFI::addr($args), FFI::addr($sub));
                    $pair          = $this->decode($sub);
                    [$key, $value] = array_values($pair);
                    $result[$key]  = $value;
                    break;

                case DBus::TYPE_ARRAY:
                    $sub = $this->ffi->new('struct DBusMessageIter');
                    $this->ffi->dbus_message_iter_recurse(FFI::addr($args), FFI::addr($sub));
                    $result[] = $this->decode($sub);
                    break;

                case DBus::TYPE_VARIANT:
                    $sub = $this->ffi->new('struct DBusMessageIter');
                    $this->ffi->dbus_message_iter_recurse(FFI::addr($args), FFI::addr($sub));
                    $subResult = $this->decode($sub);
                    $result[]  = array_shift($subResult);
                    break;

                case 0:
                    break;

                default:
                    $result[] = null;
            }
            ++$i;
        } while ($this->ffi->dbus_message_iter_next(FFI::addr($args)));

        return $result;
    }

    /**
     * @return int|float|string
     */
    private function cdata(string $type, FFI\CData $args)
    {
        assert($this->ffi instanceof PaxalDbusPhp);
        $w = FFI::new($type);
        $this->ffi->dbus_message_iter_get_basic(FFI::addr($args), FFI::addr($w));

        return $w->cdata;
    }
}
