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
                '/api/%1$s/%2$s/create',
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

        $model_label_lower = Str::of($name)->kebab()->replace('-', ' ')->lower()->toString();
        $model_route = Str::of($name)->kebab()->toString();
        $model_route_plural = Str::of($name)->plural()->kebab()->toString();
        $model_snake = Str::of($name)->snake()->toString();
        $model_snake_plural = Str::of($name)->plural()->snake()->toString();
        $model_title = Str::of($name)->title()->toString();

        $getMethod = $pathCreate->getMethod([
            'tags' => [
                $model_title,
            ],
            'summary' => sprintf(
                'Create a %1$s form.',
                $model_label_lower
            ),
            'operationId' => sprintf(
                'create_%1$s',
                $model_snake
            ),
            'responses' => [
                [
                    'code' => 200,
                    'description' => sprintf(
                        'The create %1$s information.',
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

        $getMethod?->apply();
    }

    protected function doc_controller_edit(string $name): void
    {
        $module_route = Str::of($this->c->module())->lower()->toString();
        $model_route_plural = Str::of($name)->plural()->kebab()->toString();

        $pathEdit = $this->api->controller($name)->pathEdit([
            'path' => sprintf(
                '/api/%1$s/%2$s/edit/{id}',
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
            'description' => sprintf('The %1$s id.', $model_label_lower),
            'schema' => [
                'type' => 'string',
                'format' => 'uuid',
            ],
        ]);

        $getMethod = $pathEdit->getMethod([
            'tags' => [
                $model_title,
            ],
            'summary' => sprintf(
                'Edit a %1$s form.',
                $model_label_lower
            ),
            'operationId' => sprintf(
                'edit_%1$s',
                $model_snake
            ),
            'responses' => [
                [
                    'code' => 200,
                    'description' => sprintf(
                        'The edit %1$s information.',
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

        $getMethod?->apply();
    }
}
