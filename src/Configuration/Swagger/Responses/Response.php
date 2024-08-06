<?php
/**
 * Playground
 */
declare(strict_types=1);
namespace Playground\Make\Swagger\Configuration\Swagger\Responses;

use Playground\Make\Configuration;

/**
 * \Playground\Make\Swagger\Configuration\Swagger\Responses\Response
 */
class Response extends Configuration\Configuration
{
    protected int $code = 200;

    protected ?Content $content = null;

    protected string $description = '';

    /**
     * @var array<string, mixed>
     */
    protected $properties = [
        // 'code' => 200,
        'description' => '',
        'content' => null,
    ];

    /**
     * @param array<string, mixed> $options
     */
    public function setOptions(array $options = []): self
    {
        if (! empty($options['code'])
            && is_numeric($options['code'])
            && $options['code'] > 0 && $options['code'] < 600
        ) {
            $this->code = intval($options['code']);
        }

        if (! empty($options['content'])
            && is_array($options['content'])
            && ! in_array($this->code, [
                204,
            ])
        ) {
            $this->content = new Content($options['content']);
        }

        if (! empty($options['description'])
            && is_string($options['description'])
        ) {
            $this->description = $options['description'];
        }

        // dump([
        //     '__METHOD__' => __METHOD__,
        //     '$options' => $options,
        //     '$this' => $this,
        // ]);

        return $this;
    }

    public function code(): int
    {
        return $this->code;
    }

    public function content(): ?Content
    {
        return $this->content;
    }

    public function description(): string
    {
        return $this->description;
    }
}
