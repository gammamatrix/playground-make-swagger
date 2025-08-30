<?php

/**
 * Playground
 */
declare(strict_types=1);

namespace Playground\Make\OpenAPI\Configuration\OpenAPI\Responses;

/**
 * \Playground\Make\OpenAPI\Configuration\OpenAPI\Responses\Unauthorized
 */
class Unauthorized extends Response
{
    protected int $code = 401;

    protected string $description = '';
}
