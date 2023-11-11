<?php

declare(strict_types=1);

namespace Focus\JsonApi;

use Focus\Data\Data;

/**
 * Represents a JSON:API document object
 *
 * @link https://jsonapi.org/format/#document-structure
 */
interface Document extends Data
{
    /**
     * Get the primary data as a single resource
     */
    public function resource(): Resource;

    /**
     * Get the primary data as a resource collection
     */
    public function collection(): ResourceCollection;

    /**
     * Get the included resources of a specific type
     */
    public function included(string $type): ResourceCollection;
}
