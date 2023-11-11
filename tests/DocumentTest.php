<?php

declare(strict_types=1);

namespace Focus\JsonApi\Tests;

use Focus\JsonApi\DocumentData;
use Focus\JsonApi\ResourceData;
use Focus\JsonApi\ResourceDataCollection;
use Nyholm\Psr7\Factory\Psr17Factory;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

use function assert;
use function file_get_contents;
use function is_string;
use function json_decode;

use const JSON_THROW_ON_ERROR;

#[CoversClass(DocumentData::class)]
#[UsesClass(ResourceData::class)]
#[UsesClass(ResourceDataCollection::class)]
class DocumentTest extends TestCase
{
    private Psr17Factory $httpFactory;

    protected function setUp(): void
    {
        $this->httpFactory = new Psr17Factory();
    }

    public function testShouldCreateFromValue(): void
    {
        $json = json_decode($this->mockCollection(), flags: JSON_THROW_ON_ERROR);

        $data = DocumentData::from($json);

        self::assertCount(
            expectedCount: 2,
            haystack: $data->collection(),
        );
    }

    public function testShouldCreateFromRequest(): void
    {
        $request = $this->httpFactory->createRequest(method: 'GET', uri: '/');
        $request = $request->withBody($this->httpFactory->createStream($this->mockCollection()));

        $data = DocumentData::fromRequest($request);

        self::assertCount(
            expectedCount: 2,
            haystack: $data->collection(),
        );
    }

    public function testShouldCreateFromServerRequest(): void
    {
        $request = $this->httpFactory->createServerRequest(method: 'GET', uri: '/');
        $request = $request->withParsedBody(json_decode($this->mockCollection(), flags: JSON_THROW_ON_ERROR));

        $data = DocumentData::fromRequest($request);

        self::assertCount(
            expectedCount: 2,
            haystack: $data->collection(),
        );
    }

    public function testShouldCreateFromResponse(): void
    {
        $response = $this->httpFactory->createResponse(code: 200);
        $response = $response->withBody($this->httpFactory->createStream($this->mockCollection()));

        $data = DocumentData::fromResponse($response);

        self::assertCount(
            expectedCount: 2,
            haystack: $data->collection(),
        );
    }

    public function testShouldExposeCollection(): void
    {
        $data = DocumentData::fromString($this->mockCollection());
        $collection = $data->collection();

        self::assertInstanceOf(
            expected: ResourceDataCollection::class,
            actual: $collection,
        );

        self::assertCount(
            expectedCount: 2,
            haystack: $collection,
        );

        $ids = $collection->ids();

        self::assertContains(
            needle: 'be1f8e69-8e8d-4210-a119-e476d3d52d37',
            haystack: $ids,
        );

        self::assertContains(
            needle: 'a7cfda79-fe6b-4c43-9d2c-fc12c6cfd41f',
            haystack: $ids,
        );
    }

    public function testShouldExposeResource(): void
    {
        $data = DocumentData::fromString($this->mockResource());
        $resource = $data->resource();

        self::assertInstanceOf(
            expected: ResourceData::class,
            actual: $resource,
        );

        self::assertSame(
            expected: 'a7cfda79-fe6b-4c43-9d2c-fc12c6cfd41f',
            actual: $resource->id(),
        );
    }

    public function testShouldExposeIncludes(): void
    {
        $data = DocumentData::fromString($this->mockResource());
        $people = $data->included(type: 'person');

        self::assertCount(
            expectedCount: 1,
            haystack: $people,
        );
    }

    private function mockCollection(): string
    {
        $path = __DIR__ . '/data/json-api-document-collection.json';
        $json = file_get_contents($path);

        assert(assertion: is_string($json));

        return $json;
    }

    private function mockResource(): string
    {
        $path = __DIR__ . '/data/json-api-document-resource.json';
        $json = file_get_contents($path);

        assert(assertion: is_string($json));

        return $json;
    }
}
