<?php

declare(strict_types = 1);

namespace SandwaveIo\Freshdesk\ValueObject;

use stdClass;

class Dictionary
{
    private array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function toObject(): stdClass
    {
        $object = new stdClass();
        foreach ($this->data as $key => $value) {
            $object->{$key} = $value;
        }

        return $object;
    }

    public function toArray(): array
    {
        return $this->data;
    }
}
