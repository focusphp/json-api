<?php

declare(strict_types=1);

namespace Focus\JsonApi;

use ArrayIterator;
use InvalidArgumentException;
use IteratorAggregate;
use Traversable;

use function array_is_list;
use function array_map;
use function count;
use function gettype;
use function is_array;
use function vsprintf;

/**
 * @implements IteratorAggregate<Resource>
 */
final readonly class ResourceDataCollection implements IteratorAggregate, ResourceCollection
{
    public static function from(mixed $value): self
    {
        if ($value === null) {
            return new self();
        }

        if (is_array($value) && array_is_list($value)) {
            $value = array_map(ResourceData::from(...), $value);

            return new self(...$value);
        }

        throw new InvalidArgumentException(
            message: vsprintf(format: 'Cannot create ResourceDataCollection from %s', values: [
                gettype($value),
            ]),
        );
    }

    /** @var Resource[] */
    private array $data;

    public function __construct(Resource ...$data)
    {
        $this->data = $data;
    }

    public function count(): int
    {
        return count($this->data);
    }

    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->data);
    }

    public function ids(): array
    {
        return array_map(static fn (Identifier $item) => $item->id(), $this->data);
    }
}
