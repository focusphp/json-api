<?php

declare(strict_types=1);

namespace Focus\JsonApi;

use Traversable;

/**
 * @template TValue
 * @extends Traversable<TValue>
 */
interface Collection extends Traversable
{
    /**
     * Get all the identifier values from the collection
     *
     * @return string[]
     */
    public function ids(): array;
}
