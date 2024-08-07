<?php
/**
 * Playground
 */

declare(strict_types=1);
namespace Playground\Make\Swagger\Building;

use Illuminate\Support\Str;
use Playground\Make\Swagger\Configuration\Swagger\Controller;

/**
 * \Playground\Make\Swagger\Building\BuildControllerId
 */
trait BuildControllerId
{
    protected function doc_controller_id(string $name): void
    {
        $model_route_plural = Str::of($name)->plural()->kebab()->toString();

        $pathId = $this->api->controller($name)->pathId([
            'path' => sprintf(
                '/api/%1$s/{id}',
                $model_route_plural
            ),
            'ref' => sprintf(
                'paths/%1$s/id.yml',
                $model_route_plural
            ),
        ]);

        $this->doc_controller_id_config($name, $pathId);

        // $this->doc_request_id($name, $pathId);

        $pathId->apply();

        $this->api->addPath($pathId->path(), $pathId->ref())->apply();

        $this->yaml_write($pathId->ref(), $pathId->toArray());
    }

    protected function doc_controller_id_config(
        string $name,
        Controller\PathId $pathId
    ): void {

        $model_label_lower = Str::of($name)->kebab()->replace('-', ' ')->lower()->toString();
        $model_label_plural = Str::of($name)->plural()->snake()->replace('_', ' ')->toString();
        $model_route = Str::of($name)->kebab()->toString();
        $model_route_plural = Str::of($name)->plural()->kebab()->toString();
        $model_snake = Str::of($name)->snake()->toString();
        $model_snake_plural = Str::of($name)->plural()->snake()->toString();
        $model_title = Str::of($name)->title()->toString();

        $pathId->addParameter([
            'in' => 'path',
            'name' => 'id',
            'required' => true,
            'description' => __('playground-make-swagger::params.id.description', [
                'name' => $model_label_lower,
            ]),
            'schema' => [
                'type' => 'string',
                'format' => 'uuid',
            ],
        ]);

        $getMethod = $pathId->getMethod([
            'tags' => [
                $model_title,
            ],
            'summary' => sprintf(
                'Get a %1$s by id.',
                $model_label_lower
            ),
            'operationId' => sprintf(
                'get_%1$s',
                $model_snake
            ),
            'responses' => [
                [
                    'code' => 200,
                    'description' => __('playground-make-swagger::response.data.description', [
                        'name' => $model_label_lower,
                    ]),
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

        $getMethod?->apply();

        $deleteMethod = $pathId->deleteMethod([
            'tags' => [
                $model_title,
            ],
            'summary' => sprintf(
                'Delete a %1$s by id.',
                $model_label_lower
            ),
            'operationId' => sprintf(
                'delete_%1$s',
                $model_snake
            ),
            'responses' => [
                [
                    'code' => 204,
                    'description' => sprintf(
                        'The %1$s has been deleted.',
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
                [
                    'code' => 423,
                    'description' => __('playground-make-swagger::model.locked.delete.description', [
                        'name' => $model_label_lower,
                    ]),
                ],
            ],
        ]);

        $deleteMethod?->apply();

        $patchMethod = $pathId->patchMethod([
            'tags' => [
                $model_title,
            ],
            'summary' => sprintf(
                'Patch a %1$s by id.',
                $model_label_lower
            ),
            'operationId' => sprintf(
                'patch_%1$s',
                $model_snake
            ),

            // requestBody:
            // content:
            //   application/json:
            //     schema:
            //       $ref: ../../requests/page/patch.yml
            'requestBody' => [
                'content' => [
                    'type' => 'application/json',
                    'schema' => [
                        '$ref' => sprintf(
                            '../../requests/%s/patch.yml',
                            $model_route
                        ),
                    ],
                ],
            ],

            'responses' => [
                [
                    'code' => 200,
                    'description' => sprintf(
                        'The %1$s has been patched.',
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
                [
                    'code' => 422,
                    'description' => 'Validation error',
                    'content' => [
                        'type' => 'application/json',
                        'schema' => [
                            'type' => 'object',
                            'properties' => [
                                'errors' => [
                                    'type' => 'object',
                                    'properties' => [
                                        'title' => [
                                            'type' => 'array',
                                            'items' => [
                                                'type' => 'string',
                                                'example' => __('playground-make-swagger::model.title.example'),
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
                [
                    'code' => 423,
                    'description' => __('playground-make-swagger::model.locked.patch.description', [
                        'name' => $model_label_lower,
                    ]),
                ],
            ],
        ]);

        $patchMethod?->apply();
    }
}
