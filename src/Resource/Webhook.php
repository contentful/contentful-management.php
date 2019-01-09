<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2019 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Management\Resource;

use Contentful\Management\Proxy\Extension\WebhookProxyExtension;
use Contentful\Management\Resource\Behavior\CreatableInterface;
use Contentful\Management\Resource\Behavior\DeletableTrait;
use Contentful\Management\Resource\Behavior\UpdatableTrait;
use Contentful\Management\Resource\Webhook\FilterInterface;
use Contentful\Management\SystemProperties\Webhook as SystemProperties;

/**
 * Webhook class.
 *
 * This class represents a resource with type "WebhookDefinition" in Contentful.
 *
 * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/webhooks
 */
class Webhook extends BaseResource implements CreatableInterface
{
    use WebhookProxyExtension,
        DeletableTrait,
        UpdatableTrait;

    /**
     * @var SystemProperties
     */
    protected $sys;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $url;

    /**
     * @var string|null
     */
    protected $httpBasicUsername;

    /**
     * @var string|null
     */
    protected $httpBasicPassword;

    /**
     * @var string[]
     */
    protected $topics = [];

    /**
     * @var string[]
     */
    protected $headers = [];

    /**
     * @var FilterInterface[]
     */
    protected $filters = [];

    /**
     * @var array
     */
    protected $transformation = [];

    /**
     * Webhook constructor.
     *
     * @param string   $name
     * @param string   $url
     * @param string[] $topics
     */
    public function __construct(string $name, string $url, array $topics = [])
    {
        $this->name = $name;
        $this->url = $url;
        $this->setTopics($topics);
    }

    /**
     * {@inheritdoc}
     */
    public function getSystemProperties(): SystemProperties
    {
        return $this->sys;
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize(): array
    {
        $headers = [];
        foreach ($this->headers as $key => $value) {
            $headers[] = [
                'key' => $key,
                'value' => $value,
            ];
        }

        $values = [
            'sys' => $this->sys,
            'name' => $this->name,
            'url' => $this->url,
            'topics' => $this->topics,
            'headers' => $headers,
        ];

        if ($this->httpBasicUsername) {
            $values['httpBasicUsername'] = $this->httpBasicUsername;
            if ($this->httpBasicPassword) {
                $values['httpBasicPassword'] = $this->httpBasicPassword;
            }
        }

        if ($this->filters) {
            $values['filters'] = $this->filters;
        }

        if ($this->transformation) {
            $values['transformation'] = (object) $this->transformation;
        }

        return $values;
    }

    /**
     * {@inheritdoc}
     */
    public function getHeadersForCreation(): array
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    protected function getWebhookId()
    {
        return $this->sys->getId();
    }

    /**
     * {@inheritdoc}
     */
    protected function getSpaceId()
    {
        return $this->sys->getSpace()->getId();
    }

    /**
     * {@inheritdoc}
     */
    public function asUriParameters(): array
    {
        return [
            'space' => $this->sys->getSpace()->getId(),
            'webhook' => $this->sys->getId(),
        ];
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return static
     */
    public function setName(string $name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @param string $url
     *
     * @return static
     */
    public function setUrl(string $url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getHttpBasicUsername()
    {
        return $this->httpBasicUsername;
    }

    /**
     * @param string|null $httpBasicUsername
     *
     * @return static
     */
    public function setHttpBasicUsername(string $httpBasicUsername = \null)
    {
        $this->httpBasicUsername = $httpBasicUsername;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getHttpBasicPassword()
    {
        return $this->httpBasicPassword;
    }

    /**
     * @param string|null $httpBasicPassword
     *
     * @return static
     */
    public function setHttpBasicPassword(string $httpBasicPassword = \null)
    {
        $this->httpBasicPassword = $httpBasicPassword;

        return $this;
    }

    /**
     * @return string[]
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * @param string $key
     *
     * @throws \InvalidArgumentException
     *
     * @return string
     */
    public function getHeader(string $key): string
    {
        if (!$this->hasHeader($key)) {
            throw new \InvalidArgumentException(\sprintf(
                'Invalid header key "%s" provided.',
                $key
            ));
        }

        return $this->headers[$key];
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    public function hasHeader(string $key): bool
    {
        return isset($this->headers[$key]);
    }

    /**
     * @param string $key
     * @param string $value
     *
     * @return Webhook
     */
    public function addHeader(string $key, string $value)
    {
        $this->headers[$key] = $value;

        return $this;
    }

    /**
     * @param string[] $headers An array in the form 'X-Header-Name' => 'Header Value'
     *
     * @return static
     */
    public function setHeaders(array $headers)
    {
        foreach ($headers as $key => $value) {
            if (!\is_string($key) || !\is_string($value)) {
                throw new \InvalidArgumentException(
                    'Argument "$headers" of "Webhook::setHeaders()" must be an array where all keys and values are strings.'
                );
            }
        }

        $this->headers = $headers;

        return $this;
    }

    /**
     * @param string $key
     *
     * @throws \InvalidArgumentException
     *
     * @return static
     */
    public function removeHeader(string $key)
    {
        if (!$this->hasHeader($key)) {
            throw new \InvalidArgumentException(\sprintf(
                'Invalid header key "%s" provided.',
                $key
            ));
        }

        unset($this->headers[$key]);

        return $this;
    }

    /**
     * @return string[]
     */
    public function getTopics(): array
    {
        return $this->topics;
    }

    /**
     * @param string $topic
     *
     * @return static
     */
    public function addTopic(string $topic)
    {
        $this->topics[] = $topic;
        $this->topics = \array_unique($this->topics);

        return $this;
    }

    /**
     * @param array $topics A simple list of topics; array keys will be discarded
     *
     * @return static
     */
    public function setTopics(array $topics)
    {
        $this->topics = \array_unique(\array_values($topics));

        return $this;
    }

    /**
     * @param string $topic
     *
     * @return bool
     */
    public function hasTopic(string $topic): bool
    {
        return \in_array($topic, $this->topics, \true);
    }

    /**
     * @param string $topic
     *
     * @throws \InvalidArgumentException
     *
     * @return static
     */
    public function removeTopic(string $topic)
    {
        $key = \array_search($topic, $this->topics, \true);
        if (\false === $key) {
            throw new \InvalidArgumentException(\sprintf(
                'Invalid topic "%s" provided.',
                $topic
            ));
        }

        unset($this->topics[$key]);
        $this->topics = \array_values($this->topics);

        return $this;
    }

    /**
     * @return FilterInterface[]
     */
    public function getFilters(): array
    {
        return $this->filters;
    }

    /**
     * @param FilterInterface[] $filters
     *
     * @return static
     */
    public function setFilters(array $filters)
    {
        $this->filters = $filters;

        return $this;
    }

    /**
     * @return array
     */
    public function getTransformation(): array
    {
        return $this->transformation;
    }

    /**
     * @param array $transformation
     *
     * @return static
     */
    public function setTransformation(array $transformation)
    {
        $this->transformation = $transformation;

        return $this;
    }
}
