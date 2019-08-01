<?php

declare(strict_types=1);

namespace Paxal\DBus\Type;

final class DBusVariant
{
    /** @var mixed */
    private $data;

    /**
     * @param mixed $data
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }
}
