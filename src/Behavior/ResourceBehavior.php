<?php

declare(strict_types=1);

namespace Focus\JsonApi\Behavior;

use Focus\JsonApi\Identifier;
use Focus\JsonApi\IdentifierCollection;
use Focus\JsonApi\IdentifierData;
use Focus\JsonApi\IdentifierDataCollection;

use function vsprintf;

trait ResourceBehavior
{
    abstract public function has(string $path): bool;

    abstract public function get(string $path): mixed;

    public function attribute(string $path): mixed
    {
        $path = vsprintf(format: 'attributes.%s', values: [$path]);

        return $this->get(path: $path);
    }

    public function relation(string $name): Identifier|null
    {
        $path = vsprintf(format: 'relationships.%s.data', values: [$name]);

        if ($this->has($path)) {
            return IdentifierData::from($this->get($path));
        }

        return null;
    }

    public function relations(string $name): IdentifierCollection|null
    {
        $path = vsprintf(format: 'relationships.%s.data', values: [$name]);

        if ($this->has($path)) {
            return IdentifierDataCollection::from($this->get($path));
        }

        return null;
    }
}
