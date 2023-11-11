<?php

declare(strict_types=1);

namespace Focus\JsonApi;

use Focus\Data\Data;

/**
 * Represents a JSON:API identifier object
 *
 * @link https://jsonapi.org/format/#document-resource-identifier-objects
 */
interface Identifier extends Data
{
    /**
     * Get the identifier value
     */
    public function id(): string;

    /**
     * Get the identifier type
     */
    public function type(): string;
}
