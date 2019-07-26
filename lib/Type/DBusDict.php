<?php

declare(strict_types=1);

namespace Type;

final class DBusDict
{
    private int $type;

    private array $elements;

    public function __construct(int $type, array $elements)
    {
        $this->type = $type;
        $this->elements = $elements;
    }

    public function getType(): int
    {
        return $this->type;
    }

    public function getElements(): array
    {
        return $this->elements;
    }
}
