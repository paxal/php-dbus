<?php

declare(strict_types=1);

namespace Paxal\DBus;

use Exception;

final class Signal
{
    private DBus $dbus;
    private string $object;
    private string $interface;
    private string $signal;

    public function __construct(DBus $dbus, string $object, string $interface, string $signal)
    {
        $this->dbus      = $dbus;
        $this->object    = $object;
        $this->interface = $interface;
        $this->signal    = $signal;
    }

    public function matches(string $interface, string $method) : bool
    {
        throw new Exception();
    }

    public function getDbus() : DBus
    {
        return $this->dbus;
    }

    public function getObject() : string
    {
        return $this->object;
    }

    public function getInterface() : string
    {
        return $this->interface;
    }
}
