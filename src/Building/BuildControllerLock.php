<?php
/**
 * Playground
 */

declare(strict_types=1);
namespace Playground\Make\Swagger\Building;

use Illuminate\Support\Str;
use Playground\Make\Swagger\Configuration\Swagger\Controller;

/**
 * \Playground\Make\Swagger\Building\BuildControllerLock
 */
trait BuildControllerLock
{
    protected function doc_controller_lock(
        string $name,
        string $controller_type = ''
    ): void {

        $module_route = Str::of($this->c->module())->lower()->toString();
        $model_route_plural = Str::of($name)->plural()->kebab()->toString();

        $pathLock = $this->api->controller($name)->pathLock([
            'path' => sprintf(
                '/api/%1$s/%2$s/lock/{id}',
                $module_route,
                $model_route_plural
            ),
            'ref' => sprintf(
                'paths/%1$s/lock.yml',
                $model_route_plural
            ),
        ]);

        $this->doc_controller_lock_config($name, $pathLock);

        $pathLock->apply();

        $this->api->addPath($pathLock->path(), $pathLock->ref())->apply();

        $this->yaml_write($pathLock->ref(), $pathLock->toArray());
    }

    protected function doc_controller_lock_config(
        string $name,
        Controller\PathLock $pathLock
    ): void {

        $model_label_lower = Str::of($name)->kebab()->replace('-', ' ')->lower()->toString();
        $model_route = Str::of($name)->kebab()->toString();
        $model_route_plural = Str::of($name)->plural()->kebab()->toString();
        $model_snake = Str::of($name)->snake()->toString();
        $model_snake_plural = Str::of($name)->plural()->snake()->toString();
        $model_title = Str::of($name)->title()->toString();

        $pathLock->addParameter([
            'in' => 'path',
            'name' => 'id',
            'required' => true,
            'description' => sprintf('The %1$s id.', $model_label_lower),
            'schema' => [
                'type' => 'string',
                'format' => 'uuid',
            ],
        ]);

        $deleteMethod = $pathLock->deleteMethod([
            'tags' => [
                $model_title,
            ],
            'summary' => sprintf(
                'Delete a %1$s by id.',
                $model_label_lower
            ),
            'operationId' => sprintf(
                'unlock_%1$s',
                $model_snake
            ),
            'responses' => [
                [
                    'code' => 204,
                    'description' => sprintf(
                        'The %1$s has been unlocked.',
                        $model_label_lower
                    ),
                ],
                [
                    'code' => 401,
                    'description' => 'Unauthorized',
                ],
                [
                    'code' => 403,
                    'description' => 'Forbidden',
                ],
            ],
        ]);

        $deleteMethod?->apply();

        $putMethod = $pathLock->putMethod([
            'tags' => [
                $model_title,
            ],
            'summary' => sprintf(
                'Lock a %1$s by ID.',
                $model_label_lower
            ),
            'operationId' => sprintf(
                'lock_%1$s',
                $model_snake
            ),
            'responses' => [
                [
                    'code' => 200,
                    'description' => sprintf(
                        'The unlocked %1$s.',
                        $model_label_lower
                    ),
                    'content' => [
                        'type' => 'application/json',
                        'schema' => [
                            'type' => 'object',
                            'properties' => [
                                'data' => [
                                    '$ref' => sprintf(
                                        '../../models/%s.yml',
                                        $model_route
                                    ),
                                ],
                                'meta' => [
                                    'type' => 'object',

                                ],
                            ],
                        ],
                    ],
                ],
                [
                    'code' => 401,
                    'description' => 'Unauthorized',
                ],
                [
                    'code' => 403,
                    'description' => 'Forbidden',
                ],
            ],
        ]);

        $putMethod?->apply();
    }
}
