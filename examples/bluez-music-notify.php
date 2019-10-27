<?php

declare(strict_types=1);

use Paxal\DBus\DBus;

require_once __DIR__ . '/../vendor/autoload.php';
$ffi = require_once __DIR__ . '/../load.php';

$dbus = DBus::connect(DBus::BUS_SYSTEM);
$dbus->addMatch('arg0=org.bluez.MediaPlayer1');
$lastItem = null;
while (true) {
    usleep(10);
    $message = $dbus->waitLoop();
    if ($message === null) {
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

    echo sprintf('%s %s', $track['Title'], $track['Artist'] . ' - ' . $track['Album']) . PHP_EOL;
    exec('notify-send ' . implode(' ', $escapedArguments));
}
