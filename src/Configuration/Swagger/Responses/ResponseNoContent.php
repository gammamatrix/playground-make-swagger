<?php
/**
 * Playground
 */
declare(strict_types=1);
namespace Playground\Make\Swagger\Configuration\Swagger\Responses;

/**
 * \Playground\Make\Swagger\Configuration\Swagger\Responses\ResponseNoContent
 */
class ResponseNoContent extends Response
{
    protected int $code = 204;

    protected string $description = '';
}
