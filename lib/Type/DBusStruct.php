<?php

declare(strict_types=1);

namespace Type;

final class DBusStruct
{
    private string $signature;
    private array $data;

    public function __construct(string $signature, array $data)
    {
        $this->signature = $signature;
        $this->data = $data;
    }

    public function getData(): array
    {
        return $this->data;
    }
}
