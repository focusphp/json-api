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
 * @implements IteratorAggregate<Identifier>
 */
final readonly class IdentifierDataCollection implements IteratorAggregate, IdentifierCollection
{
    public static function from(mixed $value): self
    {
        if ($value === null) {
            return new self();
        }

        if (is_array($value) && array_is_list($value)) {
            $value = array_map(IdentifierData::from(...), $value);

            return new self(...$value);
        }

        throw new InvalidArgumentException(
            message: vsprintf(format: 'Cannot create Identifiers from %s', values: [
                gettype($value),
            ]),
        );
    }

    /** @var Identifier[] */
    private array $data;

    public function __construct(Identifier ...$data)
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
