<?php

declare(strict_types=1);

namespace Focus\JsonApi;

use Focus\Data\Behavior\DataProxyBehavior;
use Focus\Data\DataProxy;
use Focus\Data\KeyedData;
use Focus\JsonApi\Behavior\IdentifierBehavior;

final class IdentifierData extends DataProxy implements Identifier
{
    use DataProxyBehavior;
    use IdentifierBehavior;

    public static function from(mixed $value): self
    {
        $data = KeyedData::tryFrom($value);

        return new self($data);
    }
}
