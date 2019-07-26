<?php

namespace {

    use FFI\CData;

    /**
     * @method FFI\CData new(string $type)
     * @method void dbus_error_init(FFI\CData &$err)
     * @method FFI\CData dbus_bus_get(int $bus, FFI\CData &$err)
     * @method bool dbus_error_is_set(FFI\CData &$err)
     * @method void dbus_error_free(FFI\CData &$err)
     * @method void dbus_bus_add_match(FFI\CData $conn, string $match, FFI\CData &$err)
     * @method void dbus_connection_flush(FFI\CData $conn)
     * @method void dbus_connection_read_write(FFI\CData $conn, int $timeout)
     * @method null|FFI\CData dbus_connection_pop_message(FFI\CData $conn)&
     * @method bool dbus_message_iter_init(FFI\CData &$msg, &FFI\CData $args)
     * @method dbus_message_unref(FFI\CData &$msg)
     * @method null|string dbus_message_get_interface(FFI\CData &$msg)
     * @method null|string dbus_message_get_path(FFI\CData &$msg)
     * @method null|string dbus_message_get_member(FFI\CData &$msg)
     */
    class PaxalDbusPhp extends FFI
    {
    }

    /**
     * @method FFI\CData new(string $type)
     * @method static FFI\CData addr(FFI\CData $val)
     */
    class FFI
    {
    }
}

namespace FFI {
    /**
     * @property $cdata
     */
    class CData
    {
    }
}

