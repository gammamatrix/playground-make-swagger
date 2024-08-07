<?php
/**
 * Playground
 */

declare(strict_types=1);
namespace Playground\Make\Swagger\Building;

use Illuminate\Support\Str;
use Playground\Make\Swagger\Configuration\Swagger\Controller;

/**
 * \Playground\Make\Swagger\Building\BuildControllerRevision
 */
trait BuildControllerRevision
{
    protected function doc_controller_revision(
        string $name,
        string $controller_type = ''
    ): void {

        $module_route = Str::of($this->c->module())->lower()->toString();
        $model_route_plural = Str::of($name)->plural()->kebab()->toString();

        $pathRevision = $this->api->controller($name)->pathRevision([
            'path' => sprintf(
                '/api/%1$s/%2$s/revision/{id}',
                $module_route,
                $model_route_plural
            ),
            'ref' => sprintf(
                'paths/%1$s/revision.yml',
                $model_route_plural
            ),
        ]);

        $this->doc_controller_revision_config($name, $pathRevision);

        $pathRevision->apply();

        $this->api->addPath($pathRevision->path(), $pathRevision->ref())->apply();

        $this->yaml_write($pathRevision->ref(), $pathRevision->toArray());
    }

    protected function doc_controller_revision_config(
        string $name,
        Controller\PathRevision $pathRevision
    ): void {

        $model_label_lower = Str::of($name)->kebab()->replace('-', ' ')->lower()->toString();
        $model_route = Str::of($name)->kebab()->toString();
        $model_route_plural = Str::of($name)->plural()->kebab()->toString();
        $model_snake = Str::of($name)->snake()->toString();
        $model_snake_plural = Str::of($name)->plural()->snake()->toString();
        $model_title = Str::of($name)->title()->toString();

        $pathRevision->addParameter([
            'in' => 'path',
            'name' => 'id',
            'required' => true,
            'description' => sprintf('The %1$s revision id.', $model_label_lower),
            'schema' => [
                'type' => 'string',
                'format' => 'uuid',
            ],
        ]);

        $getMethod = $pathRevision->getMethod([
            'tags' => [
                $model_title,
            ],
            'summary' => sprintf(
                'Show a %1$s revision by ID.',
                $model_label_lower
            ),
            'operationId' => sprintf(
                'revision_%1$s',
                $model_snake
            ),
            'responses' => [
                [
                    'code' => 200,
                    'description' => sprintf(
                        'The %1$s revision.',
                        $model_label_lower
                    ),
                    'content' => [
                        'type' => 'application/json',
                        'schema' => [
                            'type' => 'object',
                            'properties' => [
                                'data' => [
                                    '$ref' => sprintf(
                                        '../../models/%s-revision.yml',
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

        $getMethod?->apply();

        $putMethod = $pathRevision->putMethod([
            'tags' => [
                $model_title,
            ],
            'summary' => sprintf(
                'Restore a %1$s Revision by ID.',
                $model_label_lower
            ),
            'operationId' => sprintf(
                'restore_revision_%1$s',
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
