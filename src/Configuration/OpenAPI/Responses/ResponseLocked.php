<?php

/**
 * Playground
 */
declare(strict_types=1);

namespace Playground\Make\OpenAPI\Configuration\OpenAPI\Responses;

/**
 * \Playground\Make\OpenAPI\Configuration\OpenAPI\Responses\ResponseLocked
 */
class ResponseLocked extends Response
{
    protected int $code = 423;

    protected string $description = '';
}
