<?php
/**
 * Playground
 */

declare(strict_types=1);
namespace Playground\Make\Swagger\Building;

use Illuminate\Support\Str;
use Playground\Make\Swagger\Configuration\Swagger\Controller;

/**
 * \Playground\Make\Swagger\Building\BuildControllerIndex
 */
trait BuildControllerIndex
{
    protected function doc_controller_index(
        string $name,
    ): void {

        $model_route_plural = Str::of($name)->plural()->kebab()->toString();

        $pathIndex = $this->api->controller($name)->pathIndex([
            'path' => sprintf(
                '/api/%1$s',
                $model_route_plural
            ),
            'ref' => sprintf(
                'paths/%1$s/index.yml',
                $model_route_plural
            ),
        ]);

        $this->doc_controller_index_config($name, $pathIndex);

        // $this->doc_request_index($name, $pathIndex);

        $pathIndex->apply();

        $this->api->addPath($pathIndex->path(), $pathIndex->ref())->apply();

        $this->yaml_write($pathIndex->ref(), $pathIndex->toArray());
    }

    protected function doc_controller_index_config(
        string $name,
        Controller\PathIndex $pathIndex
    ): void {

        $model_label_lower = Str::of($name)->kebab()->replace('-', ' ')->lower()->toString();
        $model_label_plural = Str::of($name)->plural()->snake()->replace('_', ' ')->toString();
        $model_route = Str::of($name)->kebab()->toString();
        $model_route_plural = Str::of($name)->plural()->kebab()->toString();
        $model_snake = Str::of($name)->snake()->toString();
        $model_snake_plural = Str::of($name)->plural()->snake()->toString();
        $model_title = Str::of($name)->title()->toString();

        $getMethod = $pathIndex->getMethod([
            'tags' => [
                $model_title,
            ],
            'summary' => sprintf(
                'Get %1$s from the index.',
                $model_label_plural
            ),
            'operationId' => sprintf(
                'get_%1$s_index',
                $model_snake_plural
            ),
            'responses' => [
                [
                    'code' => 200,
                    'description' => sprintf(
                        'Get the %1$s from the index.',
                        $model_label_plural
                    ),
                    'content' => [
                        'type' => 'application/json',
                        'schema' => [
                            'type' => 'object',
                            'properties' => [
                                'data' => [
                                    'type' => 'array',
                                    'items' => [
                                        '$ref' => sprintf(
                                            '../../models/%s.yml',
                                            $model_route
                                        ),
                                    ],
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

        $postMethod = $pathIndex->postMethod([
            'tags' => [
                $model_title,
            ],
            'summary' => sprintf(
                'Create a %1$s.',
                $model_label_lower
            ),
            'operationId' => sprintf(
                'post_%1$s',
                $model_snake
            ),

            'requestBody' => [
                'content' => [
                    'type' => 'application/json',
                    'schema' => [
                        '$ref' => sprintf(
                            '../../requests/%s/post.yml',
                            $model_route
                        ),
                    ],
                ],
            ],

            'responses' => [
                [
                    'code' => 200,
                    'description' => sprintf(
                        'The created %1$s.',
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
                                                'example' => 'The title field is required.',
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
                    'description' => sprintf(
                        'The %1$s is locked. Unlock to patch.',
                        $model_label_lower
                    ),
                ],
            ],
        ]);

        $postMethod?->apply();
    }

    protected function doc_controller_index_form(string $name): void
    {

        $model_route_plural = Str::of($name)->plural()->kebab()->toString();

        $pathIndexForm = $this->api->controller($name)->pathIndexForm([
            'path' => sprintf(
                '/api/%1$s',
                $model_route_plural
            ),
            'ref' => sprintf(
                'paths/%1$s/index-form.yml',
                $model_route_plural
            ),
        ]);

        $this->doc_controller_index_form_config($name, $pathIndexForm);

        $pathIndexForm->apply();

        $this->api->addPath($pathIndexForm->path(), $pathIndexForm->ref())->apply();

        $this->yaml_write($pathIndexForm->ref(), $pathIndexForm->toArray());
    }

    protected function doc_controller_index_form_config(
        string $name,
        Controller\PathIndexForm $pathIndexForm
    ): void {

        $model_label_plural = Str::of($name)->plural()->snake()->replace('_', ' ')->toString();
        $model_route = Str::of($name)->kebab()->toString();
        $model_route_plural = Str::of($name)->plural()->kebab()->toString();
        $model_snake = Str::of($name)->snake()->toString();
        $model_snake_plural = Str::of($name)->plural()->snake()->toString();
        $model_title = Str::of($name)->title()->toString();

        $postMethod = $pathIndexForm->postMethod([
            'tags' => [
                $model_title,
            ],
            'summary' => sprintf(
                'Get %1$s from the index using POST.',
                $model_label_plural
            ),
            'operationId' => sprintf(
                'post_%1$s_index',
                $model_snake_plural
            ),
            'requestBody' => [
                'content' => [
                    'type' => 'application/json',
                    'schema' => [
                        '$ref' => sprintf(
                            '../../requests/%s/form.yml',
                            $model_snake
                        ),
                    ],
                ],
            ],
            'responses' => [
                [
                    'code' => 200,
                    'description' => sprintf(
                        'Get the %1$s from the index.',
                        $model_label_plural
                    ),
                    'content' => [
                        'type' => 'application/json',
                        'schema' => [
                            'type' => 'object',
                            'properties' => [
                                'data' => [
                                    'type' => 'array',
                                    'items' => [
                                        '$ref' => sprintf(
                                            '../../models/%s.yml',
                                            $model_route
                                        ),
                                    ],
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

        $postMethod?->apply();
    }
}
