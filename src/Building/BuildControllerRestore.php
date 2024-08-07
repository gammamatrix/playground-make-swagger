<?php
/**
 * Playground
 */

declare(strict_types=1);
namespace Playground\Make\Swagger\Building;

use Illuminate\Support\Str;
use Playground\Make\Swagger\Configuration\Swagger\Controller;

/**
 * \Playground\Make\Swagger\Building\BuildControllerRestore
 */
trait BuildControllerRestore
{
    protected function doc_controller_restore(
        string $name,
        string $controller_type = ''
    ): void {

        $model_route_plural = Str::of($name)->plural()->kebab()->toString();

        $pathRestore = $this->api->controller($name)->pathRestore([
            'path' => sprintf(
                '/api/%1$s/restore/{id}',
                $model_route_plural
            ),
            'ref' => sprintf(
                'paths/%1$s/restore.yml',
                $model_route_plural
            ),
        ]);

        $this->doc_controller_restore_config($name, $pathRestore);

        $pathRestore->apply();

        $this->api->addPath($pathRestore->path(), $pathRestore->ref())->apply();

        $this->yaml_write($pathRestore->ref(), $pathRestore->toArray());
    }

    protected function doc_controller_restore_config(
        string $name,
        Controller\PathRestore $pathRestore
    ): void {

        $model_label_lower = Str::of($name)->kebab()->replace('-', ' ')->lower()->toString();
        $model_route = Str::of($name)->kebab()->toString();
        $model_route_plural = Str::of($name)->plural()->kebab()->toString();
        $model_snake = Str::of($name)->snake()->toString();
        $model_snake_plural = Str::of($name)->plural()->snake()->toString();
        $model_title = Str::of($name)->title()->toString();

        $pathRestore->addParameter([
            'in' => 'path',
            'name' => 'id',
            'required' => true,
            'description' => sprintf('The %1$s id.', $model_label_lower),
            'schema' => [
                'type' => 'string',
                'format' => 'uuid',
            ],
        ]);

        $putMethod = $pathRestore->putMethod([
            'tags' => [
                $model_title,
            ],
            'summary' => sprintf(
                'Restore a %1$s from the trash by ID.',
                $model_label_lower
            ),
            'operationId' => sprintf(
                'restore_%1$s',
                $model_snake
            ),
            'responses' => [
                [
                    'code' => 200,
                    'description' => sprintf(
                        'The restored %1$s.',
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
