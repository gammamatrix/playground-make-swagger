<?php
/**
 * Playground
 */

declare(strict_types=1);
namespace Playground\Make\Swagger\Building;

use Illuminate\Support\Str;
use Playground\Make\Swagger\Configuration\Swagger\Controller;

/**
 * \Playground\Make\Swagger\Building\BuildControllerForm
 */
trait BuildControllerForm
{
    protected function doc_controller_create(string $name): void
    {
        $module_route = Str::of($this->c->module())->lower()->toString();
        $model_route_plural = Str::of($name)->plural()->kebab()->toString();

        $pathCreate = $this->api->controller($name)->pathCreate([
            'path' => sprintf(
                '%1$s/%2$s/%3$s/create',
                $this->route_prefix,
                $module_route,
                $model_route_plural
            ),
            'ref' => sprintf(
                'paths/%1$s/create.yml',
                $model_route_plural
            ),
        ]);

        $this->doc_controller_create_config($name, $pathCreate);

        $pathCreate->apply();

        $this->api->addPath($pathCreate->path(), $pathCreate->ref())->apply();

        $this->yaml_write($pathCreate->ref(), $pathCreate->toArray());
    }

    protected function doc_controller_create_config(
        string $name,
        Controller\PathCreate $pathCreate
    ): void {

        $module_route = Str::of($this->c->module())->lower()->toString();
        // $model_route_plural = Str::of($name)->plural()->kebab()->toString();

        $model_label_lower = Str::of($name)->kebab()->replace('-', ' ')->lower()->toString();
        $model_route = Str::of($name)->kebab()->toString();
        $model_route_plural = Str::of($name)->plural()->kebab()->toString();
        $model_snake = Str::of($name)->snake()->toString();
        $model_snake_plural = Str::of($name)->plural()->snake()->toString();
        $model_title = Str::of($name)->title()->toString();

        $description_get = __(
            $this->isResource ? 'playground-make-swagger::response.create.resource.description'
            : 'playground-make-swagger::response.create.description', [
                'name' => $model_label_lower,
            ]);

        $responses_get = [];

        if ($this->isApi || $this->isResource) {
            $responses_get[] = [
                'code' => 200,
                'description' => $description_get,
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
            ];
        }

        if ($this->isResource) {
            $responses_get[] = [
                'code' => 200,
                // Only the first description is used
                // 'description' => $description_get,
                'content' => [
                    'type' => 'text/html',
                    'schema' => [
                        'type' => 'string',
                        'example' => __('playground-make-swagger::response.create.resource.content.example', [
                            'name' => $model_label_lower,
                            'route-module' => $module_route,
                            'route-names' => $model_route_plural,
                        ]),
                    ],
                ],
            ];
        }

        $responses_get[] = [
            'code' => 401,
            'description' => 'Unauthorized',
        ];
        $responses_get[] = [
            'code' => 403,
            'description' => 'Forbidden',
        ];

        $getMethod = $pathCreate->getMethod([
            'tags' => [
                $model_title,
            ],
            'summary' => __('playground-make-swagger::request.create.description', [
                'name' => $model_label_lower,
            ]),
            'operationId' => sprintf(
                'create_%1$s',
                $model_snake
            ),
            'responses' => $responses_get,
        ]);

        $getMethod?->apply();
    }

    protected function doc_controller_edit(string $name): void
    {
        $module_route = Str::of($this->c->module())->lower()->toString();
        $model_route_plural = Str::of($name)->plural()->kebab()->toString();

        $pathEdit = $this->api->controller($name)->pathEdit([
            'path' => sprintf(
                '%1$s/%2$s/%3$s/edit/{id}',
                $this->route_prefix,
                $module_route,
                $model_route_plural
            ),
            'ref' => sprintf(
                'paths/%1$s/edit.yml',
                $model_route_plural
            ),
        ]);

        $this->doc_controller_edit_config($name, $pathEdit);

        $pathEdit->apply();

        $this->api->addPath($pathEdit->path(), $pathEdit->ref())->apply();

        $this->yaml_write($pathEdit->ref(), $pathEdit->toArray());
    }

    protected function doc_controller_edit_config(
        string $name,
        Controller\PathEdit $pathEdit
    ): void {

        $module_route = Str::of($this->c->module())->lower()->toString();

        $model_label_lower = Str::of($name)->kebab()->replace('-', ' ')->lower()->toString();
        $model_route = Str::of($name)->kebab()->toString();
        $model_route_plural = Str::of($name)->plural()->kebab()->toString();
        $model_snake = Str::of($name)->snake()->toString();
        $model_snake_plural = Str::of($name)->plural()->snake()->toString();
        $model_title = Str::of($name)->title()->toString();

        $pathEdit->addParameter([
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

        $description_get = __(
            $this->isResource ? 'playground-make-swagger::response.edit.resource.description'
            : 'playground-make-swagger::response.edit.description', [
                'name' => $model_label_lower,
            ]);

        $responses_get = [];

        if ($this->isApi || $this->isResource) {
            $responses_get[] = [
                'code' => 200,
                'description' => $description_get,
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
            ];
        }

        if ($this->isResource) {
            $responses_get[] = [
                'code' => 200,
                // Only the first description is used
                // 'description' => $description_get,
                'content' => [
                    'type' => 'text/html',
                    'schema' => [
                        'type' => 'string',
                        'example' => __('playground-make-swagger::response.edit.resource.content.example', [
                            'name' => $model_label_lower,
                            'route-module' => $module_route,
                            'route-names' => $model_route_plural,
                        ]),
                    ],
                ],
            ];
        }

        $responses_get[] = [
            'code' => 401,
            'description' => 'Unauthorized',
        ];
        $responses_get[] = [
            'code' => 403,
            'description' => 'Forbidden',
        ];

        $getMethod = $pathEdit->getMethod([
            'tags' => [
                $model_title,
            ],
            'summary' => __('playground-make-swagger::request.edit.description', [
                'name' => $model_label_lower,
            ]),
            'operationId' => sprintf(
                'edit_%1$s',
                $model_snake
            ),
            'responses' => $responses_get,
        ]);

        $getMethod?->apply();
    }
}
