<?php

declare(strict_types=1);

final class DBus
{
    public const BUS_SESSION = 0;
    public const BUS_SYSTEM = 1;
    public const BUS_STARTER = 2;

    /* Primitive types */
    public const TYPE_BYTE = 0x79;
    public const TYPE_BYTE_AS_STRING = 'y';

    public const TYPE_BOOLEAN = 0x62;
    public const TYPE_BOOLEAN_AS_STRING = 'b';

    public const TYPE_INT16 = 0x6e;
    public const TYPE_INT16_AS_STRING = 'n';

    public const TYPE_UINT16 = 0x71;
    public const TYPE_UINT16_AS_STRING = 'q';

    public const TYPE_INT32 = 0x69;
    public const TYPE_INT32_AS_STRING = 'i';

    public const TYPE_UINT32 = 0x75;
    public const TYPE_UINT32_AS_STRING = 'u';

    public const TYPE_INT64 = 0x78;
    public const TYPE_INT64_AS_STRING = 'x';

    public const TYPE_UINT64 = 0x74;
    public const TYPE_UINT64_AS_STRING = 't';

    public const TYPE_DOUBLE = 0x64;
    public const TYPE_DOUBLE_AS_STRING = 'd';

    public const TYPE_STRING = 0x73;
    public const TYPE_STRING_AS_STRING = 's';

    public const TYPE_OBJECT_PATH = 0x6f;
    public const TYPE_OBJECT_PATH_AS_STRING = 'o';

    public const TYPE_SIGNATURE = 0x67;
    public const TYPE_SIGNATURE_AS_STRING = 'g';

    public const TYPE_TYPE_UNIX_FD = 0x68;
    public const TYPE_TYPE_UNIX_FD_AS_STRING = 'h';

    /* Compound types */
    public const TYPE_ARRAY = 0x61;
    public const TYPE_ARRAY_AS_STRING = 'a';

    public const TYPE_VARIANT = 0x76;
    public const TYPE_VARIANT_AS_STRING = 'v';

    public const TYPE_STRUCT = 0x72;
    public const TYPE_STRUCT_AS_STRING = 'r';

    public const TYPE_DICT_ENTRY = 0x65;
    public const TYPE_DICT_ENTRY_AS_STRING = 'e';

    /** @var \FFI|\PaxalDbusPhp */
    public static \FFI $ffi;
    private \FFI\CData $conn;

    private static function init(): void
    {
        self::$ffi = require __DIR__.'/../load.php';
    }

    private function __construct(FFI\CData $conn)
    {
        $this->conn = $conn;
    }

    public static function connect(int $busId): self
    {
        self::init();

        $err = self::$ffi->new('struct DBusError');
        self::$ffi->dbus_error_init(FFI::addr($err));
        $conn = self::$ffi->dbus_bus_get($busId, FFI::addr($err));
        if (self::$ffi->dbus_error_is_set(FFI::addr($err))) {
            $message = $err->cdata['message'];
            self::$ffi->dbus_error_free(FFI::addr($err));
            throw new \RuntimeException("Connection Error ({$message})");
        }
        if (null === $conn) {
            throw new \RuntimeException('Unable to create connection');
        }

        return new self($conn);
    }

    public function addMatch(string $match = ''): bool
    {
        $err = self::$ffi->new('struct DBusError');
        self::$ffi->dbus_bus_add_match($this->conn, $match, FFI::addr($err));
        self::$ffi->dbus_connection_flush($this->conn);
        if (self::$ffi->dbus_error_is_set(FFI::addr($err))) {
            $message = $err->cdata['message'];
            self::$ffi->dbus_error_free(FFI::addr($err));
            trigger_error(E_USER_WARNING, "Match Error ({$message})");

            return false;
        }

        return true;
    }

    public function waitLoop(): ?Message
    {
        self::$ffi->dbus_connection_read_write($this->conn, 0);
        $msg = self::$ffi->dbus_connection_pop_message($this->conn);

        // loop again if we haven't read a message
        if (NULL === $msg) {
            return null;
        }

        $args = self::$ffi->new('struct DBusMessageIter');
        $decoder = new DBusMessageIterDecoder(self::$ffi);
        if (!self::$ffi->dbus_message_iter_init($msg, FFI::addr($args))) {
            trigger_error(E_USER_WARNING, "Message has no arguments!\n");
            return null;
        }

        $message = new Message();
        $message->interface = self::$ffi->dbus_message_get_interface($msg);
        $message->path = self::$ffi->dbus_message_get_path($msg);
        $message->member = self::$ffi->dbus_message_get_member($msg);
        $message->arguments = $decoder->decode($args);

        self::$ffi->dbus_message_unref($msg);

        return $message;
    }
}
