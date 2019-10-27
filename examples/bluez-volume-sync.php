<?php

declare(strict_types=1);

use Paxal\DBus\DBus;

const BLUEZ_TO_PA_FACTOR = 65535/127;

require_once __DIR__ . '/../vendor/autoload.php';
$ffi = require_once __DIR__ . '/../load.php';

$dbus = DBus::connect(DBus::BUS_SYSTEM);
$dbus->addMatch('arg0=org.bluez.MediaTransport1');

while (true) {
    $message = $dbus->waitLoop();
    if ($message === null) {
        usleep(10);
        continue;
    }

    if (strpos($message->path ?? '', '/org/bluez') !== 0) {
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
    if ($objectType !== 'org.bluez.MediaTransport1') {
        continue;
    }

    $volume = $arguments[1]['Volume'] ?? null;
    if ($volume === null) {
        continue;
    }

    $addr     = preg_replace('@^.*/dev_(.*?)(/.*)$@', '$1', (string) $message->path);
    $paVolume = (int) (BLUEZ_TO_PA_FACTOR * (int) $volume);
    echo 'Set volume to ' . round($paVolume / 65535 * 100) . '%' . PHP_EOL;
    exec(sprintf('pactl set-source-volume bluez_source.%s.a2dp_source %s', $addr, $paVolume));
}
