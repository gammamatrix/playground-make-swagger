<?php
/**
 * Playground
 */
declare(strict_types=1);
namespace Playground\Make\Swagger\Configuration\Swagger;

/**
 * \Playground\Make\Swagger\Configuration\Swagger\Info
 */
class Info extends SwaggerConfiguration
{
    protected string $title = '';

    protected string $description = '';

    protected string $termsOfService = '';

    protected string $version = '';

    protected ?Contact $contact = null;

    /**
     * @var array<string, mixed>
     */
    protected $properties = [
        'title' => '',
        'description' => '',
        'termsOfService' => '',
        'contact' => null,
        'version' => '',
    ];

    /**
     * @param array<string, mixed> $options
     */
    public function setOptions(array $options = []): self
    {
        if (! empty($options['title'])
            && is_string($options['title'])
        ) {
            $this->title = $options['title'];
        }

        if (! empty($options['description'])
            && is_string($options['description'])
        ) {
            $this->description = $options['description'];
        }

        if (! empty($options['termsOfService'])
            && is_string($options['termsOfService'])
        ) {
            $this->termsOfService = $options['termsOfService'];
        }

        if (! empty($options['version'])
            && is_string($options['version'])
        ) {
            $this->version = $options['version'];
        }

        if (! empty($options['contact'])
            && is_array($options['contact'])
        ) {
            $this->contact = new Contact($options['contact']);
        }

        return $this;
    }

    public function contact(): ?Contact
    {
        return $this->contact;
    }

    public function description(): string
    {
        return $this->description;
    }

    public function termsOfService(): string
    {
        return $this->termsOfService;
    }

    public function title(): string
    {
        return $this->title;
    }

    public function version(): string
    {
        return $this->version;
    }
}
