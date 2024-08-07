<?php
/**
 * Playground
 */

declare(strict_types=1);
namespace Playground\Make\Swagger\Building;

use Illuminate\Support\Str;
use Playground\Make\Swagger\Configuration\Swagger\Controller;

/**
 * \Playground\Make\Swagger\Building\BuildController
 */
trait BuildController
{
    protected string $build_controller_description = '';

    /**
     * @var array<string, mixed>
     */
    protected array $build_controller_properties = [];

    protected function doc_controller(): void
    {
        $this->build_controller_properties = [];

        $name = $this->c->name();
        if (empty($name)) {
            $this->components->error('Docs: The name must be set in the [controller] configuration');

            return;
        }
        $controller_type = $this->c->controller_type();
        if (in_array($controller_type, [
            'playground-api',
            'playground-resource',
            'resource',
            'api',
        ])) {
            // Add the tag for the model.
            $this->api->addTag($name);

            $this->doc_controller_id($name, $controller_type);
            $this->doc_controller_index($name, $controller_type);
            $this->doc_controller_index_form($name, $controller_type);
            $this->doc_controller_lock($name, $controller_type);
            $this->doc_controller_restore($name, $controller_type);
            $this->doc_controller_create($name, $controller_type);
            $this->doc_controller_edit($name, $controller_type);

        }
    }

    protected function doc_controller_id(
        string $name,
        string $controller_type = ''
    ): void {

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

        $model_label_lower = Str::of($name)->lower()->toString();
        $model_label_plural = Str::of($name)->plural()->snake()->replace('_', ' ')->toString();
        $model_route = Str::of($name)->kebab()->toString();
        $model_route_plural = Str::of($name)->plural()->kebab()->toString();
        $model_snake = Str::of($name)->snake()->toString();
        $model_snake_plural = Str::of($name)->plural()->snake()->toString();
        $model_title = Str::of($name)->title()->toString();

        $pathId->addParameter($name, [
            'in' => 'path',
            'name' => 'id',
            'required' => true,
            'description' => sprintf('The %1$s id.', $model_label_lower),
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
                    'description' => sprintf(
                        'The %1$s data.',
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
                    'description' => sprintf(
                        'The %1$s is locked. Unlock to delete.',
                        $model_label_lower
                    ),
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

        $patchMethod?->apply();
    }

    protected function doc_controller_index(
        string $name,
        string $controller_type = ''
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

        $model_label_lower = Str::of($name)->lower()->toString();
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

    protected function doc_controller_index_form(
        string $name,
        string $controller_type = ''
    ): void {

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

    protected function doc_controller_lock(
        string $name,
        string $controller_type = ''
    ): void {

        $model_route_plural = Str::of($name)->plural()->kebab()->toString();

        $pathLock = $this->api->controller($name)->pathLock([
            'path' => sprintf(
                '/api/%1$s/lock/{id}',
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

        $model_label_lower = Str::of($name)->lower()->toString();
        $model_route = Str::of($name)->kebab()->toString();
        $model_route_plural = Str::of($name)->plural()->kebab()->toString();
        $model_snake = Str::of($name)->snake()->toString();
        $model_snake_plural = Str::of($name)->plural()->snake()->toString();
        $model_title = Str::of($name)->title()->toString();

        $pathLock->addParameter($name, [
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

        $model_label_lower = Str::of($name)->lower()->toString();
        $model_route = Str::of($name)->kebab()->toString();
        $model_route_plural = Str::of($name)->plural()->kebab()->toString();
        $model_snake = Str::of($name)->snake()->toString();
        $model_snake_plural = Str::of($name)->plural()->snake()->toString();
        $model_title = Str::of($name)->title()->toString();

        $pathRestore->addParameter($name, [
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

    protected function doc_controller_create(
        string $name,
        string $controller_type = ''
    ): void {

        $model_route_plural = Str::of($name)->plural()->kebab()->toString();

        $pathCreate = $this->api->controller($name)->pathCreate([
            'path' => sprintf(
                '/api/%1$s/create',
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

        $model_label_lower = Str::of($name)->lower()->toString();
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

    protected function doc_controller_edit(
        string $name,
        string $controller_type = ''
    ): void {

        $model_route_plural = Str::of($name)->plural()->kebab()->toString();

        $pathEdit = $this->api->controller($name)->pathEdit([
            'path' => sprintf(
                '/api/%1$s/edit/{id}',
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

        $model_label_lower = Str::of($name)->lower()->toString();
        $model_route = Str::of($name)->kebab()->toString();
        $model_route_plural = Str::of($name)->plural()->kebab()->toString();
        $model_snake = Str::of($name)->snake()->toString();
        $model_snake_plural = Str::of($name)->plural()->snake()->toString();
        $model_title = Str::of($name)->title()->toString();

        $pathEdit->addParameter($name, [
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
