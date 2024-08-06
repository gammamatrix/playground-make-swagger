<?php
/**
 * Playground
 */
declare(strict_types=1);
namespace Playground\Make\Swagger\Configuration\Swagger;

/**
 * \ Playground\Make\Swagger\Configuration\Swagger\Contact
 */
class Contact extends SwaggerConfiguration
{
    protected string $email = '';

    /**
     * @var array<string, mixed>
     */
    protected $properties = [
        'email' => '',
    ];

    /**
     * @param array<string, mixed> $options
     */
    public function setOptions(array $options = []): self
    {
        if (! empty($options['email'])
            && is_string($options['email'])
        ) {
            $this->email = $options['email'];
        }

        return $this;
    }

    public function email(): string
    {
        return $this->email;
    }
}
