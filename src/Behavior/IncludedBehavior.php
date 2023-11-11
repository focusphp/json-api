<?php

declare(strict_types=1);

namespace Focus\JsonApi\Behavior;

use Focus\JsonApi\ResourceCollection;
use Focus\JsonApi\ResourceDataCollection;

use function vsprintf;

trait IncludedBehavior
{
    abstract public function search(string $path): mixed;

    public function included(string $type): ResourceCollection
    {
        $data = $this->search(path: vsprintf(format: "included[?type == '%s']", values: [$type]));

        return ResourceDataCollection::from($data);
    }
}
