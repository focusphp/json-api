<?php

declare(strict_types=1);

namespace Focus\JsonApi\Tests;

use Focus\JsonApi\Identifier;
use Focus\JsonApi\IdentifierCollection;
use Focus\JsonApi\IdentifierData;
use Focus\JsonApi\IdentifierDataCollection;
use Focus\JsonApi\ResourceData;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

use function assert;
use function file_get_contents;
use function is_string;
use function json_decode;

use const JSON_THROW_ON_ERROR;

#[CoversClass(IdentifierData::class)]
#[CoversClass(ResourceData::class)]
#[UsesClass(IdentifierDataCollection::class)]
class ResourceTest extends TestCase
{
    public function testShouldErrorWhenCreatedFromList(): void
    {
        self::expectException(
            exception: InvalidArgumentException::class,
        );

        ResourceData::from([1, 2, 3]);
    }

    public function testShouldHaveIdentifierBehavior(): void
    {
        $data = ResourceData::from($this->mockResource());

        self::assertInstanceOf(
            expected: Identifier::class,
            actual: $data,
        );

        self::assertSame(
            expected: 'article',
            actual: $data->type(),
        );

        self::assertSame(
            expected: 'be1f8e69-8e8d-4210-a119-e476d3d52d37',
            actual: $data->id(),
        );
    }

    public function testShouldGetAttributes(): void
    {
        $data = ResourceData::from($this->mockResource());

        self::assertSame(
            expected: 'PHP is Awesome',
            actual: $data->attribute(path: 'title'),
        );
    }

    public function testShouldGetOneRelationship(): void
    {
        $data = ResourceData::from($this->mockResource());
        $author = $data->relation(name: 'author');

        self::assertInstanceOf(
            expected: Identifier::class,
            actual: $author,
        );

        self::assertSame(
            expected: 'person',
            actual: $author->type(),
        );

        self::assertSame(
            expected: 'e63f48b5-1958-4046-8653-9ced1b794a5c',
            actual: $author->id(),
        );
    }

    public function testShouldReturnNullForUndefinedRelationship(): void
    {
        $data = ResourceData::from($this->mockResource());
        $instructor = $data->relation(name: 'instructor');

        self::assertNull(
            actual: $instructor,
        );
    }

    public function testShouldGetManyRelationships(): void
    {
        $data = ResourceData::from($this->mockResource());
        $contributors = $data->relations(name: 'contributors');

        self::assertInstanceOf(
            expected: IdentifierCollection::class,
            actual: $contributors,
        );

        self::assertCount(
            expectedCount: 2,
            haystack: $contributors,
        );

        foreach ($contributors as $contributor) {
            self::assertInstanceOf(
                expected: Identifier::class,
                actual: $contributor,
            );

            self::assertSame(
                expected: 'person',
                actual: $contributor->type(),
            );
        }

        $ids = $contributors->ids();

        self::assertContains(
            needle: 'f78dc85f-5bf4-4665-acd1-ace039081f11',
            haystack: $ids,
        );

        self::assertContains(
            needle: 'f16a9c15-8e33-44e6-81a4-9b58a87beaab',
            haystack: $ids,
        );
    }

    public function testShouldReturnNullForUndefinedManyRelationships(): void
    {
        $data = ResourceData::from($this->mockResource());
        $instructors = $data->relations(name: 'instructors');

        self::assertNull(
            actual: $instructors,
        );
    }

    private function mockResource(): object
    {
        $path = __DIR__ . '/data/json-api-resource.json';
        $json = file_get_contents($path);

        assert(assertion: is_string($json));

        return json_decode($json, flags: JSON_THROW_ON_ERROR);
    }
}
