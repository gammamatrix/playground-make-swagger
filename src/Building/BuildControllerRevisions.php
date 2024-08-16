<?php
/**
 * Playground
 */

declare(strict_types=1);
namespace Playground\Make\Swagger\Building;

use Illuminate\Support\Str;
use Playground\Make\Swagger\Configuration\Swagger\Controller;

/**
 * \Playground\Make\Swagger\Building\BuildControllerRevisions
 */
trait BuildControllerRevisions
{
    protected function doc_controller_revisions(string $name): void
    {
        $module_route = Str::of($this->c->module())->lower()->toString();
        $model_route_plural = Str::of($name)->plural()->kebab()->toString();

        $pathRevisions = $this->api->controller($name)->pathRevisions([
            'path' => sprintf(
                '%1$s/%2$s/%3$s/{id}/revisions',
                $this->route_prefix,
                $module_route,
                $model_route_plural
            ),
            'ref' => sprintf(
                'paths/%1$s/revisions.yml',
                $model_route_plural
            ),
        ]);

        $this->doc_controller_revisions_config($name, $pathRevisions);

        $pathRevisions->apply();

        $this->api->addPath($pathRevisions->path(), $pathRevisions->ref())->apply();

        $this->yaml_write($pathRevisions->ref(), $pathRevisions->toArray());
    }

    protected function doc_controller_revisions_config(
        string $name,
        Controller\PathRevisions $pathRevisions
    ): void {

        $model_label_lower = Str::of($name)->kebab()->replace('-', ' ')->lower()->toString();
        $model_route = Str::of($name)->kebab()->toString();
        $model_route_plural = Str::of($name)->plural()->kebab()->toString();
        $model_snake = Str::of($name)->snake()->toString();
        $model_snake_plural = Str::of($name)->plural()->snake()->toString();
        $model_title = Str::of($name)->title()->toString();

        $pathRevisions->addParameter([
            'in' => 'path',
            'name' => 'id',
            'required' => true,
            'description' => sprintf('The %1$s id.', $model_label_lower),
            'schema' => [
                'type' => 'string',
                'format' => 'uuid',
            ],
        ]);

        $getMethod = $pathRevisions->getMethod([
            'tags' => [
                $model_title,
            ],
            'summary' => sprintf(
                'Get the revisions of a %1$s.',
                $model_label_lower
            ),
            'operationId' => sprintf(
                'revision_index_%1$s',
                $model_snake
            ),
            'responses' => [
                [
                    'code' => 200,
                    'description' => sprintf(
                        'The %1$s revisions.',
                        $model_label_lower
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
                                            '../../models/%s-revision.yml',
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
    }
}
