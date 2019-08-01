<?php

declare(strict_types=1);

use Paxal\DBus\DBus;
use Paxal\DBus\DBusMessageIterDecoder;
use Paxal\DBus\Message;

require_once __DIR__ . '/../vendor/autoload.php';
$ffi = require_once __DIR__ . '/../load.php';

$err = $ffi->new('struct DBusError');
$ffi->dbus_error_init(FFI::addr($err));
$args = $ffi->new('struct DBusMessageIter');
$conn = $ffi->dbus_bus_get(DBus::BUS_SYSTEM, FFI::addr($err));

$ffi->dbus_bus_add_match(
    $conn,
    'arg0=org.bluez.MediaPlayer1',
    FFI::addr($err)
);
$ffi->dbus_connection_flush($conn);
if ($ffi->dbus_error_is_set(FFI::addr($err))) {
    fprintf(stderr, "Match Error (%s)\n", $err->message);
    exit(1);
}

$decoder = new DBusMessageIterDecoder($ffi);

$lastItem = null;
while (true) {
    $ffi->dbus_connection_read_write($conn, 0);
    $msg = $ffi->dbus_connection_pop_message($conn);
    $end = microtime(true);

    // loop again if we haven't read a message
    if ($msg === null) {
        usleep(100000);
        continue;
    }

    if (! $ffi->dbus_message_iter_init($msg, FFI::addr($args))) {
        error_log("Message has no arguments!\n");
        continue;
    }

    $message            = new Message();
    $message->interface = $ffi->dbus_message_get_interface($msg);
    $message->path      = $ffi->dbus_message_get_path($msg);
    $message->member    = $ffi->dbus_message_get_member($msg);
    $message->arguments = $decoder->decode($args);

    $ffi->dbus_message_unref($msg);

    if (strpos($message->path, '/org/bluez') !== 0) {
        continue;
    }

    if ($message->interface !== 'org.freedesktop.DBus.Properties' || $message->member !== 'PropertiesChanged') {
        continue;
    }

    $arguments = $message->arguments;
    if (! is_array($arguments)) {
        continue;
    }

    $objectType = $arguments[0];
    if ($objectType !== 'org.bluez.MediaPlayer1') {
        continue;
    }

    $track = $arguments[1]['Track'] ?? null;
    if ($track === null) {
        continue;
    }

    $item = $track['Item'] ?? null;
    if ($item === null) {
        continue;
    }

    if ($item === $lastItem) {
        continue;
    }

    $lastItem         = $item;
    $arguments        = ['-t', '5000', '-a', 'Music', $track['Title'], $track['Artist'] . ' - ' . $track['Album']];
    $escapedArguments = array_map('escapeshellarg', $arguments);
    exec('notify-send ' . implode(' ', $escapedArguments));
}
