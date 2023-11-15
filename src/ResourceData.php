<?php

declare(strict_types=1);

namespace Focus\JsonApi;

use Focus\Data\Behavior\DataProxyBehavior;
use Focus\Data\DataProxy;
use Focus\Data\KeyedData;
use Focus\JsonApi\Behavior\IdentifierBehavior;
use Focus\JsonApi\Behavior\ResourceBehavior;
use InvalidArgumentException;

use function array_is_list;
use function gettype;
use function is_array;
use function vsprintf;

final readonly class ResourceData extends DataProxy implements Resource
{
    use DataProxyBehavior;
    use IdentifierBehavior;
    use ResourceBehavior;

    public static function from(mixed $value): ResourceData
    {
        if (is_array($value) && array_is_list($value)) {
            throw new InvalidArgumentException(
                message: vsprintf(format: 'Cannot create ResourceData from %s', values: [
                    gettype($value),
                ]),
            );
        }

        $data = KeyedData::tryFrom($value);

        return new ResourceData($data);
    }
}
