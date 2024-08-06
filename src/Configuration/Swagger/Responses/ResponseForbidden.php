<?php
/**
 * Playground
 */
declare(strict_types=1);
namespace Playground\Make\Swagger\Configuration\Swagger\Responses;

/**
 * \Playground\Make\Swagger\Configuration\Swagger\Responses\ResponseForbidden
 */
class ResponseForbidden extends Response
{
    protected int $code = 403;

    protected string $description = '';
}
