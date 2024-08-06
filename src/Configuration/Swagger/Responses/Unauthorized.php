<?php
/**
 * Playground
 */
declare(strict_types=1);
namespace Playground\Make\Swagger\Configuration\Swagger\Responses;

/**
 * \ Playground\Make\Swagger\Configuration\Swagger\Responses\Unauthorized
 */
class Unauthorized extends Response
{
    protected int $code = 401;

    protected string $description = '';
}
