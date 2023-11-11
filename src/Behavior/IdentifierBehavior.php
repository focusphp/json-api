<?php

declare(strict_types=1);

namespace Focus\JsonApi\Behavior;

trait IdentifierBehavior
{
    abstract public function get(string $path): mixed;

    public function id(): string
    {
        return $this->get(path: 'id')
            ?? $this->get(path: 'lid');
    }

    public function type(): string
    {
        return $this->get(path: 'type');
    }
}
