<?php

declare(strict_types=1);

namespace Focus\JsonApi\Tests;

use Focus\JsonApi\Resource;
use Focus\JsonApi\ResourceData;
use Focus\JsonApi\ResourceDataCollection;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

use function assert;
use function file_get_contents;
use function is_string;
use function json_decode;

use const JSON_THROW_ON_ERROR;

#[CoversClass(ResourceDataCollection::class)]
#[UsesClass(ResourceData::class)]
class ResourceCollectionTest extends TestCase
{
    public function testShouldErrorWithAssociativeArray(): void
    {
        self::expectException(
            exception: InvalidArgumentException::class,
        );

        ResourceDataCollection::from(['invalid' => true]);
    }

    public function testShouldCreateEmptyCollectionForNull(): void
    {
        $data = ResourceDataCollection::from(value: null);

        self::assertCount(
            expectedCount: 0,
            haystack: $data,
        );
    }

    public function testShouldCreateCollectionForList(): void
    {
        $data = ResourceDataCollection::from($this->mockCollection());

        self::assertCount(
            expectedCount: 2,
            haystack: $data,
        );

        foreach ($data as $item) {
            self::assertInstanceOf(
                expected: Resource::class,
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
