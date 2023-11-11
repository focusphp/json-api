<?php

declare(strict_types=1);

namespace Focus\JsonApi;

use Focus\Data\Behavior\DataProxyBehavior;
use Focus\Data\DataProxy;
use Focus\Data\JsonData;
use Focus\Data\KeyedData;
use Focus\JsonApi\Behavior\IncludedBehavior;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

final class DocumentData extends DataProxy implements Document
{
    use DataProxyBehavior;
    use IncludedBehavior;

    public static function from(mixed $value): self
    {
        $data = KeyedData::tryFrom($value);

        return new self($data);
    }

    public static function fromString(string $json): self
    {
        $data = JsonData::fromString($json);

        return new self($data);
    }

    public static function fromRequest(RequestInterface $request, bool $useParsedBody = true): self
    {
        $data = JsonData::fromRequest($request, $useParsedBody);

        return new self($data);
    }

    public static function fromResponse(ResponseInterface $response): self
    {
        $data = JsonData::fromResponse($response);

        return new self($data);
    }

    public function resource(): Resource
    {
        return ResourceData::from($this->get(path: 'data'));
    }

    public function collection(): ResourceCollection
    {
        return ResourceDataCollection::from($this->get(path: 'data'));
    }
}
