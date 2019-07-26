<?php

declare(strict_types=1);

final class Object_
{
    private DBus $dbus;
    private Message $message;
    private string $destination;
    private string $path;
    private string $interface;
    private int $direction;

    public function __construct(DBus $dbus, Message $message, string $destination, string $path, string $interface, int $direction)
    {
        $this->dbus = $dbus;
        $this->message = $message;
        $this->destination = $destination;
        $this->path = $path;
        $this->interface = $interface;
        $this->direction = $direction;
    }

    public function getDbus(): DBus
    {
        return $this->dbus;
    }

    public function getDestination(): string
    {
        return $this->destination;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getInterface(): string
    {
        return $this->interface;
    }
}
