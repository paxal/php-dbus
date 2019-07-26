<?php

declare(strict_types=1);

final class Message
{
    public ?string $path = null;
    public ?string $interface = null;
    public ?string $member = null;
    public array $arguments;
}
