<?php

declare(strict_types=1);

namespace Paxal\DBus\Type;

final class DBusDict
{
    private int $type;

    private array $elements;

    /**
     * @param array<string, mixed> $elements
     */
    public function __construct(int $type, array $elements)
    {
        $this->type     = $type;
        $this->elements = $elements;
    }

    public function getType() : int
    {
        return $this->type;
    }

    /**
     * @return array<string, mixed>
     */
    public function getElements() : array
    {
        return $this->elements;
    }
}
