<?php

declare(strict_types=1);

namespace Focus\JsonApi\Tests;

use Focus\JsonApi\Identifier;
use Focus\JsonApi\IdentifierData;
use Focus\JsonApi\IdentifierDataCollection;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

use function assert;
use function file_get_contents;
use function is_string;
use function json_decode;

use const JSON_THROW_ON_ERROR;

#[CoversClass(IdentifierDataCollection::class)]
#[UsesClass(IdentifierData::class)]
class IdentifierCollectionTest extends TestCase
{
    public function testShouldErrorWithAssociativeArray(): void
    {
        self::expectException(
            exception: InvalidArgumentException::class,
        );

        IdentifierDataCollection::from(['invalid' => true]);
    }

    public function testShouldCreateEmptyCollectionForNull(): void
    {
        $data = IdentifierDataCollection::from(value: null);

        self::assertCount(
            expectedCount: 0,
            haystack: $data,
        );
    }

    public function testShouldCreateCollectionForList(): void
    {
        $data = IdentifierDataCollection::from($this->mockCollection());

        self::assertCount(
            expectedCount: 2,
            haystack: $data,
        );

        foreach ($data as $item) {
            self::assertInstanceOf(
                expected: Identifier::class,
                actual: $item,
            );
        }

        $ids = $data->ids();

        self::assertContains(
            needle: 'be1f8e69-8e8d-4210-a119-e476d3d52d37',
            haystack: $ids,
        );

        self::assertContains(
            needle: 'a7cfda79-fe6b-4c43-9d2c-fc12c6cfd41f',
            haystack: $ids,
        );
    }

    /**
     * @return object[]
     */
    private function mockCollection(): array
    {
        $path = __DIR__ . '/data/json-api-collection.json';
        $json = file_get_contents($path);

        assert(assertion: is_string($json));

        return json_decode($json, flags: JSON_THROW_ON_ERROR);
    }
}
