<?php
/**
 * Playground
 */

declare(strict_types=1);
namespace Playground\Make\Swagger\Building;

/**
 * \Playground\Make\Swagger\Building\BuildServers
 */
trait BuildServers
{
    public function doc_servers(): void
    {
        /**
         * @var array<string, array<string, mixed>> $config
         */
        $config = config('playground-make-swagger.servers');

        $servers = [];

        $pairs = [];

        foreach ($this->api->servers() as $server) {
            $pairs[] = $server->description().':'.$server->url();
        }

        // dd([
        //     '$config' => $config,
        //     '$pairs' => $pairs,
        //     // '$this->api->servers()' => $this->api->servers(),
        // ]);

        if (is_array($config)) {
            foreach ($config as $env => $meta) {
                if (is_array($meta)
                    && ! empty($meta['enable'])
                    && ! empty($meta['url'])
                    && is_string($meta['url'])
                ) {
                    $description = $meta['description'] ?: '';
                    $pair = $description.':'.$meta['url'];

                    if (! in_array($pair, $pairs)) {
                        $servers[] = [
                            'url' => $meta['url'],
                            'description' => $meta['description'] ?: '',
                        ];
                    }
                }
            }
        }

        $this->api->setOptions([
            'servers' => $servers,
        ]);
        $this->api->apply();

        // dd([
        //     '__METHOD__' => __METHOD__,
        //     '$servers' => $servers,
        //     '$this->options()' => $this->options(),
        //     '$this->c' => $this->c,
        //     '$this->api' => $this->api,
        //     // '$this->model' => $this->model?->toArray(),
        //     // '$this->modelRevision' => $this->modelRevision?->toArray(),
        //     '$this->searches' => $this->searches,
        // ]);
    }
}
