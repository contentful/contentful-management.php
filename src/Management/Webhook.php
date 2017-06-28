<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */

namespace Contentful\Management;

class Webhook implements SpaceScopedResourceInterface, Creatable, Updatable, Deletable
{
    /**
     * @var SystemProperties
     */
    private $sys;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $url;

    /**
     * @var string|null
     */
    private $httpBasicUsername;

    /**
     * @var string|null
     */
    private $httpBasicPassword;

    /**
     * @var string[]
     */
    private $topics = [];

    /**
     * @var string[]
     */
    private $headers = [];

    /**
     * @param string $name
     * @param string $url
     * @param string[] $topics
     */
    public function __construct(string $name, string $url, array $topics = [])
    {
        $this->sys = SystemProperties::withType('WebhookDefinition');
        $this->name = $name;
        $this->url = $url;
        $this->setTopics($topics);
    }

    /**
     * @return SystemProperties
     */
    public function getSystemProperties(): SystemProperties
    {
        return $this->sys;
    }

    public function getResourceUrlPart(): string
    {
        return 'webhook_definitions';
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
     * @return $this
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
     * @return $this
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
     * @return $this
     */
    public function setHttpBasicUsername(string $httpBasicUsername = null)
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
     * @return $this
     */
    public function setHttpBasicPassword(string $httpBasicPassword = null)
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
     * @return string
     *
     * @throws \InvalidArgumentException
     */
    public function getHeader(string $key): string
    {
        if (!$this->hasHeader($key)) {
            throw new \InvalidArgumentException(sprintf("Invalid header key provided: '%s'", $key));
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
     * @return $this
     */
    public function setHeaders(array $headers)
    {
        foreach ($headers as $key => $value) {
            if (!is_string($key) or !is_string($value)) {
                throw new \InvalidArgumentException('Argument of Webhook::setHeaders() must be an array where all keys and values are strings');
            }
        }

        $this->headers = $headers;

        return $this;
    }

    /**
     * @param string $key
     *
     * @return $this
     *
     * @throws \InvalidArgumentException
     */
    public function removeHeader(string $key)
    {
        if (!$this->hasHeader($key)) {
            throw new \InvalidArgumentException(sprintf("Invalid header key provided: '%s'", $key));
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
     * @return $this
     */
    public function addTopic(string $topic)
    {
        $this->topics[] = $topic;
        $this->topics = array_unique($this->topics);

        return $this;
    }

    /**
     * @param array $topics A simple list of topics; array keys will be discarded
     *
     * @return $this
     */
    public function setTopics(array $topics)
    {
        $this->topics = array_unique(array_values($topics));

        return $this;
    }

    /**
     * @param string $topic
     *
     * @return bool
     */
    public function hasTopic(string $topic): bool
    {
        return in_array($topic, $this->topics);
    }

    /**
     * @param string $topic
     *
     * @return $this
     *
     * @throws \InvalidArgumentException
     */
    public function removeTopic(string $topic)
    {
        $key = array_search($topic, $this->topics);
        if ($key === false) {
            throw new \InvalidArgumentException(sprintf("Invalid header key provided: '%s'", $key));
        }

        unset($this->topics[$key]);

        return $this;
    }

    /**
     * Returns an object to be used by `json_encode` to serialize objects of this class.
     *
     * @return object
     *
     * @see http://php.net/manual/en/jsonserializable.jsonserialize.php JsonSerializable::jsonSerialize
     */
    public function jsonSerialize()
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

        return (object) $values;
    }
}
