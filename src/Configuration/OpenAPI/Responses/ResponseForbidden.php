<?php

/**
 * Playground
 */
declare(strict_types=1);

namespace Playground\Make\OpenAPI\Configuration\OpenAPI\Responses;

/**
 * \Playground\Make\OpenAPI\Configuration\OpenAPI\Responses\ResponseForbidden
 */
class ResponseForbidden extends Response
{
    protected int $code = 403;

    protected string $description = '';
}
