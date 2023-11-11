<?php

declare(strict_types=1);

namespace Focus\JsonApi;

use Focus\Data\Data;

/**
 * Represents a JSON:API resource object
 *
 * @link https://jsonapi.org/format/#document-resource-objects
 */
interface Resource extends Data, Identifier
{
    /**
     * Get an attribute value from the resource
     *
     * This is a shortcut for get("attribute.$path").
     */
    public function attribute(string $path): mixed;

    /**
     * Get an identifier for a to-one relationship
     */
    public function relation(string $name): Identifier|null;

    /**
     * Get the identifiers for a to-many relationship
     */
    public function relations(string $name): IdentifierCollection|null;
}
