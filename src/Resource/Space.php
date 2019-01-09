<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2019 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Management\Resource;

use Contentful\Management\Proxy\Extension\SpaceProxyExtension;
use Contentful\Management\Resource\Behavior\CreatableInterface;
use Contentful\Management\Resource\Behavior\DeletableTrait;
use Contentful\Management\Resource\Behavior\UpdatableTrait;
use Contentful\Management\SystemProperties\Space as SystemProperties;
use function GuzzleHttp\json_encode as guzzle_json_encode;

/**
 * Space class.
 *
 * This class represents a resource with type "Space" in Contentful.
 *
 * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/spaces
 * @see https://www.contentful.com/r/knowledgebase/spaces-and-organizations/
 */
class Space extends BaseResource implements CreatableInterface
{
    use SpaceProxyExtension,
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
    protected $organizationId;

    /**
     * @var string|null
     */
    protected $defaultLocale;

    /**
     * Space constructor.
     *
     * @param string      $name
     * @param string      $organizationId
     * @param string|null $defaultLocale
     */
    public function __construct(string $name, string $organizationId, string $defaultLocale = \null)
    {
        $this->name = $name;
        $this->organizationId = $organizationId;
        $this->defaultLocale = $defaultLocale;
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
        return [
            'sys' => $this->sys,
            'name' => $this->name,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function asRequestBody(): string
    {
        $body = $this->jsonSerialize();

        unset($body['sys']);

        if ($this->defaultLocale) {
            $body['defaultLocale'] = $this->defaultLocale;
        }

        return guzzle_json_encode((object) $body, \JSON_UNESCAPED_UNICODE);
    }

    /**
     * {@inheritdoc}
     */
    public function asUriParameters(): array
    {
        return [
            'space' => $this->sys->getId(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function getSpaceId()
    {
        return $this->sys->getId();
    }

    /**
     * {@inheritdoc}
     */
    public function getHeadersForCreation(): array
    {
        return ['X-Contentful-Organization' => $this->organizationId];
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
}
