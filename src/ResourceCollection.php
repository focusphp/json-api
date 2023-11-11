<?php

declare(strict_types=1);

namespace Focus\JsonApi;

use Countable;

/**
 * @extends Collection<Resource>
 */
interface ResourceCollection extends Collection, Countable
{
}
