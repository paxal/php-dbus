<?php

declare(strict_types=1);

namespace Paxal\DBus\Type;

final class DBusStruct
{
    private string $signature;
    private array $data;

    /**
     * @param array<string, mixed> $data
     */
    public function __construct(string $signature, array $data)
    {
        $this->signature = $signature;
        $this->data      = $data;
    }

    /**
     * @return array<string, mixed>
     */
    public function getData() : array
    {
        return $this->data;
    }
}
