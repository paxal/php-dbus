<?php

declare(strict_types=1);

namespace Paxal\DBus;

final class Message
{
    /** @var string|null */
    public ?string $path = null;
    /** @var string|null */
    public ?string $interface = null;
    /** @var string|null */
    public ?string $member = null;
    /** @var array */
    public array $arguments;
}
