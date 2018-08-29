<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2018 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Management\Resource;

use Contentful\Core\Api\Link;

/**
 * ApiKey class.
 */
abstract class ApiKey extends BaseResource
{
    /**
     * @var string
     */
    protected $name = '';

    /**
     * @var string|null
     */
    protected $description;

    /**
     * @var string|null
     */
    protected $accessToken;

    /**
     * @var Link[]
     */
    protected $environments = [];

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
     * @return string|null
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     *
     * @return static
     */
    public function setDescription(string $description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getAccessToken()
    {
        return $this->accessToken;
    }

    /**
     * Returns an array to be used by "json_encode" to serialize objects of this class.
     *
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            'sys' => $this->sys,
            'name' => $this->name,
            'description' => $this->description,
            'accessToken' => $this->accessToken,
            'environments' => $this->environments,
        ];
    }

    /**
     * @param Link[] $environments
     *
     * @return self
     */
    public function setEnvironments(array $environments)
    {
        $this->environments = $environments;

        return $this;
    }

    /**
     * @return Link[]
     */
    public function getEnvironments(): array
    {
        return $this->environments;
    }

    /**
     * @param Link $environment
     *
     * @return self
     */
    public function addEnvironment(Link $environment)
    {
        $this->environments[] = $environment;

        return $this;
    }
}
