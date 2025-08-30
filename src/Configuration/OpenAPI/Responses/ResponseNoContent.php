<?php

/**
 * Playground
 */
declare(strict_types=1);

namespace Playground\Make\OpenAPI\Configuration\OpenAPI\Responses;

/**
 * \Playground\Make\OpenAPI\Configuration\OpenAPI\Responses\ResponseNoContent
 */
class ResponseNoContent extends Response
{
    protected int $code = 204;

    protected string $description = '';
}
