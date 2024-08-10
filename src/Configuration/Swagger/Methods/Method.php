<?php
/**
 * Playground
 */
declare(strict_types=1);
namespace Playground\Make\Swagger\Configuration\Swagger\Methods;

use Playground\Make\Configuration;
use Playground\Make\Swagger\Configuration\Swagger\Responses\Response;

/**
 * \Playground\Make\Swagger\Configuration\Swagger\Methods\Method
 */
class Method extends Configuration\Configuration
{
    protected string $summary = '';

    protected string $operationId = '';

    protected ?RequestBody $requestBody = null;

    /**
     * @var array<int, Response>
     */
    protected array $responses = [];

    /**
     * @var array<int, string>
     */
    protected array $tags = [];

    /**
     * @var array<string, mixed>
     */
    protected $properties = [
        'tags' => [],
        'summary' => '',
        'operationId' => '',
        'requestBody' => null,
        'responses' => [],
    ];

    /**
     * @param array<string, mixed> $options
     */
    public function setOptions(array $options = []): self
    {
        parent::setOptions($options);

        if (! empty($options['summary'])
            && is_string($options['summary'])
        ) {
            $this->summary = $options['summary'];
        }

        if (! empty($options['operationId'])
            && is_string($options['operationId'])
        ) {
            $this->operationId = $options['operationId'];
        }

        if (! empty($options['requestBody'])
            && is_array($options['requestBody'])
        ) {
            $this->requestBody = new RequestBody($options['requestBody']);
            $this->requestBody->apply();
        }

        if (! empty($options['responses'])
            && is_array($options['responses'])
        ) {
            foreach ($options['responses'] as $i => $response) {
                if (is_array($response)) {
                    $this->addResponse($response);
                }
            }
        }

        if (! empty($options['tags'])
            && is_array($options['tags'])
        ) {
            foreach ($options['tags'] as $i => $tag) {
                if (is_string($tag) && $tag && ! in_array($tag, $this->tags)) {
                    $this->tags[] = $tag;
                }
            }
        }
        // dump([
        //     '$options' => $options,
        // ]);

        return $this;
    }

    /**
     * @param array<string, string> $meta
     */
    public function addResponse(array $meta): self
    {
        $response = new Response($meta);
        $response->apply();
        $this->responses[] = $response;

        return $this;
    }

    public function summary(): string
    {
        return $this->summary;
    }

    public function operationId(): string
    {
        return $this->operationId;
    }

    public function requestBody(): ?RequestBody
    {
        return $this->requestBody;
    }

    /**
     * @return array<int, Response>
     */
    public function responses(): array
    {
        return $this->responses;
    }

    /**
     * @return array<int, string>
     */
    public function tags(): array
    {
        return $this->tags;
    }

    public function jsonSerialize(): mixed
    {
        $properties = [];

        $tags = $this->tags();
        if ($tags) {
            $properties['tags'] = $tags;
        }

        $properties['summary'] = $this->summary();

        if ($this->operationId()) {
            $properties['operationId'] = $this->operationId();
        }

        $requestBody = $this->requestBody();

        if ($requestBodyContent = $requestBody?->content()) {
            $properties['requestBody'] = [
                'content' => $requestBodyContent->toArray(),
            ];
        }

        $responses = $this->responses();
        if ($responses) {
            $properties['responses'] = [];
            foreach ($responses as $i => $response) {

                if (empty($properties['responses'][$response->code()])) {
                    $properties['responses'][$response->code()] = [
                        'description' => $response->description(),
                    ];
                }

                $content = $response->content();
                // dump([
                //     '$response' => $response,
                //     '$content' => $content,
                // ]);
                if ($content) {
                    if (empty($properties['responses'][$response->code()]['content'])) {
                        $properties['responses'][$response->code()]['content'] = [];
                    }
                    $properties['responses'][$response->code()]['content'][$content->type()] = $content->toArray();
                }
            }
        }

        // if (in_array($this->operationId(), [
        //     'create_page',
        // ])) {
        //     dd([
        //         '$properties' => $properties,
        //         '$responses' => $responses,
        //         '$requestBody' => $requestBody,
        //     ]);
        // }

        return $properties;
    }
}
// get:
//   tags:
//     - Page
//   summary: 'Create a page form.'
//   operationId: create_page
//   responses:
//     200:
//       description: 'The create information (JSON) or form (HTML).'
//       content:
//         application/json:
//           schema:
//             type: object
//             properties:
//               data:
//                 $ref: ../../models/page.yml
//               meta:
//                 type: object
//         text/html:
//           schema:
//             type: string
//             example: '<html><body><form method="POST" action="/resource/cms/pages">Create a page</form></body></html>'
//     401:
//       description: Unauthorized
//     403:
//       description: Forbidden
