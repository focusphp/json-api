<?php

declare(strict_types=1);

namespace Focus\JsonApi;

use Countable;

/**
 * @extends Collection<Identifier>
 */
interface IdentifierCollection extends Collection, Countable
{
}
