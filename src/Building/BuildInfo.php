<?php
/**
 * Playground
 */

declare(strict_types=1);
namespace Playground\Make\Swagger\Building;

/**
 * \Playground\Make\Swagger\Building\BuildInfo
 */
trait BuildInfo
{
    public function doc_info(): void
    {
        $options = [];

        $isResource = in_array($this->c->type(), [
            'resource',
            'playground-resource',
            // 'playground-resource-index',
        ]);
        $isApi = in_array($this->c->type(), [
            'api',
            'playground-api',
            // 'playground-api-index',
        ]);

        $type = '';

        if ($isResource) {
            $type = 'Resource';
        } elseif ($isApi) {
            $type = 'API';
        }

        if ($this->c->skeleton()) {
            $module = $this->c->module();
            $organization = $this->c->organization();
            $options['title'] = trim(__('playground-make-swagger::api.info.title', [
                'organization' => $organization,
                'module' => $module,
                'type' => $type,
            ]));

            if ($module === 'CMS') {
                $system = 'Content Management System';
            } elseif ($module === 'CRM') {
                $system = 'Client Relationship Management System';
            } elseif ($module === 'DAM') {
                $system = 'Digital Asset Management System';
            } else {
                $system = trim($module.' System');
            }

            if (in_array($this->c->type(), [
                'playground-resource',
                'playground-resource-index',
            ])) {
                $message = 'playground-make-package::composer.resource.description';
            } else {
                $message = 'playground-make-package::composer.api.description';
            }
            $options['description'] = __($message, [
                'organization' => $organization,
                'module' => $module,
                'system' => $system,
            ]);

        }

        $version = config('playground-make-swagger.version');

        if ($version && is_string($version)) {
            $options['version'] = $version;
        }

        $this->api->setOptions([
            'info' => $options,
        ]);

        $this->api->info()?->apply();

        $this->api->apply();

        // dd([
        //     '__METHOD__' => __METHOD__,
        //     '$this->c' => $this->c,
        //     '$this->api' => $this->api,
        //     // '$this->model' => $this->model?->toArray(),
        //     // '$this->modelRevision' => $this->modelRevision?->toArray(),
        //     '$options' => $options,
        //     '$version' => $version,
        //     '$isResource' => $isResource,
        //     '$isApi' => $isApi,
        //     '$type' => $type,
        //     '$this->c->type()' => $this->c->type(),
        //     '$this->options()' => $this->options(),
        //     '$this->searches' => $this->searches,
        //     '$this->api' => $this->api->toArray(),
        // ]);
    }
}
